<?php

/*************************************************
 *
 * Snoopy - the PHP net client
 * Author: Monte Ohrt <monte@ispi.net>
 * Copyright (c): 1999-2000 ispi, all rights reserved
 * Version: 1.0
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * You may contact the author of Snoopy by e-mail at:
 * monte@ispi.net
 *
 * Or, write to:
 * Monte Ohrt
 * CTO, ispi
 * 237 S. 70th suite 220
 * Lincoln, NE 68510
 *
 * The latest version of Snoopy can be obtained from:
 * https://snoopy.sourceforge.com
 *************************************************/
class Snoopy
{
    /**** Public variables ****/

    /* user definable vars */

    public $host       = 'www.php.net';        // host name we are connecting to
    public $port       = 80;                    // port we are connecting to
    public $proxy_host = '';                    // proxy host to use
    public $proxy_port = '';                    // proxy port to use
    public $agent      = 'Snoopy v1.0';        // agent we masquerade as
    public $referer    = '';                    // referer info to pass
    public $cookies    = [];            // array of cookies to pass
    // $cookies["username"]="joe";
    public $rawheaders = [];            // array of raw headers to send
    // $rawheaders["Content-type"]="text/html";

    public $maxredirs        = 5;                    // http redirection depth maximum. 0 = disallow
    public $lastredirectaddr = '';                // contains address of last redirected address
    public $offsiteok        = true;                // allows redirection off-site
    public $maxframes        = 0;                    // frame content depth maximum. 0 = disallow
    public $expandlinks      = true;                // expand links to fully qualified URLs.
    // this only applies to fetchlinks()
    // or submitlinks()
    public $passcookies = true;                // pass set cookies back through redirects
    // NOTE: this currently does not respect
    // dates, domains or paths.

    public $user = '';                    // user for http authentication
    public $pass = '';                    // password for http authentication

    // http accept types
    public $accept = 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*';

    public $results = '';                    // where the content is put

    public $error         = '';                    // error messages sent here
    public $response_code = '';                    // response code returned from server
    public $headers       = [];            // headers returned from server sent here
    public $maxlength     = 500000;                // max return data length (body)
    public $read_timeout  = 0;                    // timeout on read operations, in seconds
    // supported only since PHP 4 Beta 4
    // set to 0 to disallow timeouts
    public $timed_out = false;                // if a read operation timed out
    public $status    = 0;                    // http request status

    public $curl_path = '/usr/bin/curl';
    // Snoopy will use cURL for fetching
    // SSL content if a full system path to
    // the cURL binary is supplied here.
    // set to false if you do not have
    // cURL installed. See https://curl.haxx.se
    // for details on installing cURL.
    // Snoopy does *not* use the cURL
    // library functions built into php,
    // as these functions are not stable
    // as of this Snoopy release.

    // send Accept-encoding: gzip?
    public $use_gzip = true;

    /**** Private variables ****/

    public $_maxlinelen = 4096;                // max line length (headers)

    public $_httpmethod    = 'GET';                // default http request method
    public $_httpversion   = 'HTTP/1.0';            // default http request version
    public $_submit_method = 'POST';                // default submit method
    public $_submit_type   = 'application/x-www-form-urlencoded';    // default submit type
    public $_mime_boundary = '';                    // MIME boundary for multipart/form-data submit type
    public $_redirectaddr  = false;                // will be set if page fetched is a redirect
    public $_redirectdepth = 0;                    // increments on an http redirect
    public $_frameurls     = [];            // frame src urls
    public $_framedepth    = 0;                    // increments on frame depth

    public $_isproxy    = false;                // set if using a proxy server
    public $_fp_timeout = 30;                    // timeout for socket connection

    /*======================================================================*\
        Function:	fetch
        Purpose:	fetch the contents of a web page
                    (and possibly other protocols in the
                    future like ftp, nntp, gopher, etc.)
        Input:		$URI	the location of the page to fetch
        Output:		$this->results	the output text from the fetch
    \*======================================================================*/

    /**
     * @param $URI
     * @return bool
     */
    public function fetch($URI)
    {
        //preg_match("|^([^:]+)://([^:/]+)(:[\d]+)*(.*)|",$URI,$URI_PARTS);
        $URI_PARTS = parse_url($URI);
        if (!empty($URI_PARTS['user'])) {
            $this->user = $URI_PARTS['user'];
        }
        if (!empty($URI_PARTS['pass'])) {
            $this->pass = $URI_PARTS['pass'];
        }

        switch ($URI_PARTS['scheme']) {
            case 'http':
                $this->host = $URI_PARTS['host'];
                if (!empty($URI_PARTS['port'])) {
                    $this->port = $URI_PARTS['port'];
                }
                if ($this->_connect($fp)) {
                    if ($this->_isproxy) {
                        // using proxy, send entire URI
                        $this->_httprequest($URI, $fp, $URI, $this->_httpmethod);
                    } else {
                        $path = $URI_PARTS['path'] . (isset($URI_PARTS['query']) ? '?' . $URI_PARTS['query'] : '');
                        // no proxy, send only the path
                        $this->_httprequest($path, $fp, $URI, $this->_httpmethod);
                    }

                    $this->_disconnect($fp);

                    if ($this->_redirectaddr) {
                        /* url was redirected, check if we've hit the max depth */
                        if ($this->maxredirs > $this->_redirectdepth) {
                            // only follow redirect if it's on this site, or offsiteok is true
                            if (preg_match('|^https://' . preg_quote($this->host) . '|i', $this->_redirectaddr) || $this->offsiteok) {
                                /* follow the redirect */
                                $this->_redirectdepth++;
                                $this->lastredirectaddr = $this->_redirectaddr;
                                $this->fetch($this->_redirectaddr);
                            }
                        }
                    }

                    if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
                        $frameurls        = $this->_frameurls;
                        $this->_frameurls = [];

                        while (list(, $frameurl) = each($frameurls)) {
                            if ($this->_framedepth < $this->maxframes) {
                                $this->fetch($frameurl);
                                $this->_framedepth++;
                            } else {
                                break;
                            }
                        }
                    }
                } else {
                    return false;
                }
                return true;
                break;
            case 'https':
                if (!$this->curl_path || (!is_executable($this->curl_path))) {
                    $this->error = "Bad curl ($this->curl_path), can't fetch HTTPS \n";
                    return false;
                }
                $this->host = $URI_PARTS['host'];
                if (!empty($URI_PARTS['port'])) {
                    $this->port = $URI_PARTS['port'];
                }
                if ($this->_isproxy) {
                    // using proxy, send entire URI
                    $this->_httpsrequest($URI, $URI, $this->_httpmethod);
                } else {
                    $path = $URI_PARTS['path'] . ($URI_PARTS['query'] ? '?' . $URI_PARTS['query'] : '');
                    // no proxy, send only the path
                    $this->_httpsrequest($path, $URI, $this->_httpmethod);
                }

                if ($this->_redirectaddr) {
                    /* url was redirected, check if we've hit the max depth */
                    if ($this->maxredirs > $this->_redirectdepth) {
                        // only follow redirect if it's on this site, or offsiteok is true
                        if (preg_match('|^https://' . preg_quote($this->host) . '|i', $this->_redirectaddr) || $this->offsiteok) {
                            /* follow the redirect */
                            $this->_redirectdepth++;
                            $this->lastredirectaddr = $this->_redirectaddr;
                            $this->fetch($this->_redirectaddr);
                        }
                    }
                }

                if ($this->_framedepth < $this->maxframes && count($this->_frameurls) > 0) {
                    $frameurls        = $this->_frameurls;
                    $this->_frameurls = [];

                    while (list(, $frameurl) = each($frameurls)) {
                        if ($this->_framedepth < $this->maxframes) {
                            $this->fetch($frameurl);
                            $this->_framedepth++;
                        } else {
                            break;
                        }
                    }
                }
                return true;
                break;
            default:
                // not a valid protocol
                $this->error = 'Invalid protocol "' . $URI_PARTS['scheme'] . '"\n';
                return false;
                break;
        }
        return true;
    }



    /*======================================================================*\
        Private functions
    \*======================================================================*/

    /*======================================================================*\
        Function:	_striplinks
        Purpose:	strip the hyperlinks from an html document
        Input:		$document	document to strip.
        Output:		$match		an array of the links
    \*======================================================================*/

    /**
     * @param $document
     * @return mixed
     */
    public function _striplinks($document)
    {
        preg_match_all(
            "'<\s*a\s+.*href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx",
            $document,
            $links
        );

        // catenate the non-empty matches from the conditional subpattern

        while (list($key, $val) = each($links[2])) {
            if (!empty($val)) {
                $match[] = $val;
            }
        }

        while (list($key, $val) = each($links[3])) {
            if (!empty($val)) {
                $match[] = $val;
            }
        }

        // return the links
        return $match;
    }

    /*======================================================================*\
        Function:	_stripform
        Purpose:	strip the form elements from an html document
        Input:		$document	document to strip.
        Output:		$match		an array of the links
    \*======================================================================*/

    /**
     * @param $document
     * @return string
     */
    public function _stripform($document)
    {
        preg_match_all("'<\/?(FORM|INPUT|SELECT|TEXTAREA|(OPTION))[^<>]*>(?(2)(.*(?=<\/?(option|select)[^<>]*>[\r\n]*)|(?=[\r\n]*))|(?=[\r\n]*))'Usi", $document, $elements);

        // catenate the matches
        $match = implode("\r\n", $elements[0]);

        // return the links
        return $match;
    }

    /*======================================================================*\
        Function:	_striptext
        Purpose:	strip the text from an html document
        Input:		$document	document to strip.
        Output:		$text		the resulting text
    \*======================================================================*/

    /**
     * @param $document
     * @return string|string[]|null
     */
    public function _striptext($document)
    {
        // I didn't use preg eval (//e) since that is only available in PHP 4.0.
        // so, list your entities one by one here. I included some of the
        // more common ones.

        $search  = [
            "'<script[^>]*?>.*?</script>'si",    // strip out javascript
            "'<[\/\!]*?[^<>]*?>'si",            // strip out html tags
            "'([\r\n])[\s]+'",                    // strip out white space
            "'&(quote|#34);'i",                    // replace html entities
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
        ];
        $replace = [
            '',
            '',
            "\\1",
            '"',
            '&',
            '<',
            '>',
            ' ',
            chr(161),
            chr(162),
            chr(163),
            chr(169),
        ];

        $text = preg_replace($search, $replace, $document);

        return $text;
    }

    /*======================================================================*\
        Function:	_expandlinks
        Purpose:	expand each link into a fully qualified URL
        Input:		$links			the links to qualify
                    $URI			the full URI to get the base from
        Output:		$expandedLinks	the expanded links
    \*======================================================================*/

    /**
     * @param $links
     * @param $URI
     * @return string|string[]|null
     */
    public function _expandlinks($links, $URI)
    {
        preg_match("/^[^\?]+/", $URI, $match);

        $match = preg_replace("|/[^\/\.]+\.[^\/\.]+$|", '', $match[0]);

        $search = [
            '|^https://' . preg_quote($this->host) . '|i',
            "|^(?!https://)(\/)?(?!mailto:)|i",
            "|/\./|",
            "|/[^\/]+/\.\./|",
        ];

        $replace = [
            '',
            $match . '/',
            '/',
            '/',
        ];

        $expandedLinks = preg_replace($search, $replace, $links);

        return $expandedLinks;
    }

    /*======================================================================*\
        Function:	_httprequest
        Purpose:	go get the http data from the server
        Input:		$url		the url to fetch
                    $fp			the current open file pointer
                    $URI		the full URI
                    $body		body contents to send if any (POST)
        Output:
    \*======================================================================*/

    /**
     * @param        $url
     * @param        $fp
     * @param        $URI
     * @param        $http_method
     * @param string $content_type
     * @param string $body
     * @return bool
     */
    public function _httprequest($url, $fp, $URI, $http_method, $content_type = '', $body = '')
    {
        if ($this->passcookies && $this->_redirectaddr) {
            $this->setcookies();
        }

        $URI_PARTS = parse_url($URI);
        if (empty($url)) {
            $url = '/';
        }
        $headers = $http_method . ' ' . $url . ' ' . $this->_httpversion . "\r\n";
        if (!empty($this->agent)) {
            $headers .= 'User-Agent: ' . $this->agent . "\r\n";
        }
        if (!empty($this->host) && !isset($this->rawheaders['Host'])) {
            $headers .= 'Host: ' . $this->host . "\r\n";
        }
        if (!empty($this->accept)) {
            $headers .= 'Accept: ' . $this->accept . "\r\n";
        }

        if ($this->use_gzip) {
            // make sure PHP was built with --with-zlib
            // and we can handle gzipp'ed data
            if (function_exists(gzinflate())) {
                $headers .= "Accept-encoding: gzip\r\n";
            } else {
                trigger_error('use_gzip is on, but PHP was built without zlib support.' . '  Requesting file(s) without gzip encoding.', E_USER_NOTICE);
            }
        }

        if (!empty($this->referer)) {
            $headers .= 'Referer: ' . $this->referer . "\r\n";
        }
        if (!empty($this->cookies)) {
            if (!is_array($this->cookies)) {
                $this->cookies = (array)$this->cookies;
            }

            reset($this->cookies);
            if (count($this->cookies) > 0) {
                $cookie_headers .= 'Cookie: ';
                foreach ($this->cookies as $cookieKey => $cookieVal) {
                    $cookie_headers .= $cookieKey . '=' . urlencode($cookieVal) . '; ';
                }
                $headers .= substr($cookie_headers, 0, -2) . "\r\n";
            }
        }
        if (!empty($this->rawheaders)) {
            if (!is_array($this->rawheaders)) {
                $this->rawheaders = (array)$this->rawheaders;
            }
            while (list($headerKey, $headerVal) = each($this->rawheaders)) {
                $headers .= $headerKey . ': ' . $headerVal . "\r\n";
            }
        }
        if (!empty($content_type)) {
            $headers .= "Content-type: $content_type";
            if ('multipart/form-data' == $content_type) {
                $headers .= '; boundary=' . $this->_mime_boundary;
            }
            $headers .= "\r\n";
        }
        if (!empty($body)) {
            $headers .= 'Content-length: ' . strlen($body) . "\r\n";
        }
        if (!empty($this->user) || !empty($this->pass)) {
            $headers .= 'Authorization: BASIC ' . base64_encode($this->user . ':' . $this->pass) . "\r\n";
        }

        $headers .= "\r\n";

        // set the read timeout if needed
        if ($this->read_timeout > 0) {
            stream_set_timeout($fp, $this->read_timeout);
        }
        $this->timed_out = false;

        fwrite($fp, $headers . $body, strlen($headers . $body));

        $this->_redirectaddr = false;
        unset($this->headers);

        // content was returned gzip encoded?
        $is_gzipped = false;

        while ($currentHeader = fgets($fp, $this->_maxlinelen)) {
            if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
                $this->status = -100;
                return false;
            }

            //	if($currentHeader == "\r\n")
            if (preg_match("/^\r?\n$/", $currentHeader)) {
                break;
            }

            // if a header begins with Location: or URI:, set the redirect
            if (preg_match('/^(Location:|URI:)/i', $currentHeader)) {
                // get URL portion of the redirect
                preg_match("/^(Location:|URI:)\s+(.*)/", rtrim($currentHeader), $matches);
                // look for :// in the Location header to see if hostname is included
                if (!preg_match("|\:\/\/|", $matches[2])) {
                    // no host in the path, so prepend
                    $this->_redirectaddr = $URI_PARTS['scheme'] . '://' . $this->host . ':' . $this->port;
                    // eliminate double slash
                    if (!preg_match('|^/|', $matches[2])) {
                        $this->_redirectaddr .= '/' . $matches[2];
                    } else {
                        $this->_redirectaddr .= $matches[2];
                    }
                } else {
                    $this->_redirectaddr = $matches[2];
                }
            }

            if (preg_match('|^HTTP/|', $currentHeader)) {
                if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|", $currentHeader, $status)) {
                    $this->status = $status[1];
                }
                $this->response_code = $currentHeader;
            }

            if (preg_match('/Content-Encoding: gzip/', $currentHeader)) {
                $is_gzipped = true;
            }

            $this->headers[] = $currentHeader;
        }

        # $results = fread($fp, $this->maxlength);
        $results = '';
        while ($data = fread($fp, $this->maxlength)) {
            $results .= $data;
            if (strlen($results) > $this->maxlength) {
                break;
            }
        }

        // gunzip
        if ($is_gzipped) {
            // per https://www.php.net/manual/en/function.gzencode.php
            $results = substr($results, 10);
            $results = gzinflate($results);
        }

        if ($this->read_timeout > 0 && $this->_check_timeout($fp)) {
            $this->status = -100;
            return false;
        }

        // check if there is a a redirect meta tag

        if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]+URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results, $match)) {
            $this->_redirectaddr = $this->_expandlinks($match[1], $URI);
        }

        // have we hit our frame depth and is there frame src to fetch?
        if (($this->_framedepth < $this->maxframes) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
            $this->results[] = $results;
            for ($x = 0, $xMax = count($match[1]); $x < $xMax; $x++) {
                $this->_frameurls[] = $this->_expandlinks($match[1][$x], $URI_PARTS['scheme'] . '://' . $this->host);
            }
        } // have we already fetched framed content?
        elseif (is_array($this->results)) {
            $this->results[] = $results;
        } // no framed content
        else {
            $this->results = $results;
        }

        return true;
    }

    /*======================================================================*\
        Function:	_httpsrequest
        Purpose:	go get the https data from the server using curl
        Input:		$url		the url to fetch
                    $URI		the full URI
                    $body		body contents to send if any (POST)
        Output:
    \*======================================================================*/

    /**
     * @param        $url
     * @param        $URI
     * @param        $http_method
     * @param string $content_type
     * @param string $body
     * @return bool
     */
    public function _httpsrequest($url, $URI, $http_method, $content_type = '', $body = '')
    {
        if ($this->passcookies && $this->_redirectaddr) {
            $this->setcookies();
        }

        $headers = [];

        $URI_PARTS = parse_url($URI);
        if (empty($url)) {
            $url = '/';
        }
        // GET ... header not needed for curl
        //$headers[] = $http_method." ".$url." ".$this->_httpversion;
        if (!empty($this->agent)) {
            $headers[] = 'User-Agent: ' . $this->agent;
        }
        if (!empty($this->host)) {
            $headers[] = 'Host: ' . $this->host;
        }
        if (!empty($this->accept)) {
            $headers[] = 'Accept: ' . $this->accept;
        }
        if (!empty($this->referer)) {
            $headers[] = 'Referer: ' . $this->referer;
        }
        if (!empty($this->cookies)) {
            if (!is_array($this->cookies)) {
                $this->cookies = (array)$this->cookies;
            }

            reset($this->cookies);
            if (count($this->cookies) > 0) {
                $cookie_str = 'Cookie: ';
                foreach ($this->cookies as $cookieKey => $cookieVal) {
                    $cookie_str .= $cookieKey . '=' . urlencode($cookieVal) . '; ';
                }
                $headers[] = substr($cookie_str, 0, -2);
            }
        }
        if (!empty($this->rawheaders)) {
            if (!is_array($this->rawheaders)) {
                $this->rawheaders = (array)$this->rawheaders;
            }
            while (list($headerKey, $headerVal) = each($this->rawheaders)) {
                $headers[] = $headerKey . ': ' . $headerVal;
            }
        }
        if (!empty($content_type)) {
            if ('multipart/form-data' == $content_type) {
                $headers[] = "Content-type: $content_type; boundary=" . $this->_mime_boundary;
            } else {
                $headers[] = "Content-type: $content_type";
            }
        }
        if (!empty($body)) {
            $headers[] = 'Content-length: ' . strlen($body);
        }
        if (!empty($this->user) || !empty($this->pass)) {
            $headers[] = 'Authorization: BASIC ' . base64_encode($this->user . ':' . $this->pass);
        }

        for ($curr_header = 0, $curr_headerMax = count($headers); $curr_header < $curr_headerMax; $curr_header++) {
            $cmdline_params .= ' -H "' . $headers[$curr_header] . '"';
        }

        if (!empty($body)) {
            $cmdline_params .= " -d \"$body\"";
        }

        if ($this->read_timeout > 0) {
            $cmdline_params .= ' -m ' . $this->read_timeout;
        }

        $headerfile = uniqid(time(), true);

        # accept self-signed certs
        $cmdline_params .= ' -k';
        exec($this->curl_path . " -D \"/tmp/$headerfile\"" . escapeshellcmd($cmdline_params) . ' ' . escapeshellcmd($URI), $results, $return);

        if ($return) {
            $this->error = "Error: cURL could not retrieve the document, error $return.";
            return false;
        }

        $results = implode("\r\n", $results);

        $result_headers = file("/tmp/$headerfile");

        $this->_redirectaddr = false;
        unset($this->headers);

        for ($currentHeader = 0, $currentHeaderMax = count($result_headers); $currentHeader < $currentHeaderMax; $currentHeader++) {
            // if a header begins with Location: or URI:, set the redirect
            if (preg_match('/^(Location: |URI: )/i', $result_headers[$currentHeader])) {
                // get URL portion of the redirect
                preg_match('/^(Location: |URI:)(.*)/', rtrim($result_headers[$currentHeader]), $matches);
                // look for :// in the Location header to see if hostname is included
                if (!preg_match("|\:\/\/|", $matches[2])) {
                    // no host in the path, so prepend
                    $this->_redirectaddr = $URI_PARTS['scheme'] . '://' . $this->host . ':' . $this->port;
                    // eliminate double slash
                    if (!preg_match('|^/|', $matches[2])) {
                        $this->_redirectaddr .= '/' . $matches[2];
                    } else {
                        $this->_redirectaddr .= $matches[2];
                    }
                } else {
                    $this->_redirectaddr = $matches[2];
                }
            }

            if (preg_match('|^HTTP/|', $result_headers[$currentHeader])) {
                $this->response_code = $result_headers[$currentHeader];
                if (preg_match("|^HTTP/[^\s]*\s(.*?)\s|", $this->response_code, $match)) {
                    $this->status = $match[1];
                }
            }
            $this->headers[] = $result_headers[$currentHeader];
        }

        // check if there is a a redirect meta tag

        if (preg_match("'<meta[\s]*http-equiv[^>]*?content[\s]*=[\s]*[\"\']?\d+;[\s]+URL[\s]*=[\s]*([^\"\']*?)[\"\']?>'i", $results, $match)) {
            $this->_redirectaddr = $this->_expandlinks($match[1], $URI);
        }

        // have we hit our frame depth and is there frame src to fetch?
        if (($this->_framedepth < $this->maxframes) && preg_match_all("'<frame\s+.*src[\s]*=[\'\"]?([^\'\"\>]+)'i", $results, $match)) {
            $this->results[] = $results;
            for ($x = 0, $xMax = count($match[1]); $x < $xMax; $x++) {
                $this->_frameurls[] = $this->_expandlinks($match[1][$x], $URI_PARTS['scheme'] . '://' . $this->host);
            }
        } // have we already fetched framed content?
        elseif (is_array($this->results)) {
            $this->results[] = $results;
        } // no framed content
        else {
            $this->results = $results;
        }

        unlink("/tmp/$headerfile");

        return true;
    }

    /*======================================================================*\
        Function:	setcookies()
        Purpose:	set cookies for a redirection
    \*======================================================================*/

    public function setcookies()
    {
        for ($x = 0, $xMax = count($this->headers); $x < $xMax; $x++) {
            if (preg_match("/^set-cookie:[\s]+([^=]+)=([^;]+)/i", $this->headers[$x], $match)) {
                $this->cookies[$match[1]] = $match[2];
            }
        }
    }

    /*======================================================================*\
        Function:	_check_timeout
        Purpose:	checks whether timeout has occurred
        Input:		$fp	file pointer
    \*======================================================================*/

    /**
     * @param $fp
     * @return bool
     */
    public function _check_timeout($fp)
    {
        if ($this->read_timeout > 0) {
            $fp_status = stream_get_meta_data($fp);
            if ($fp_status['timed_out']) {
                $this->timed_out = true;
                return true;
            }
        }
        return false;
    }

    /*======================================================================*\
        Function:	_connect
        Purpose:	make a socket connection
        Input:		$fp	file pointer
    \*======================================================================*/

    /**
     * @param $fp
     * @return bool
     */
    public function _connect(&$fp)
    {
        if (!empty($this->proxy_host) && !empty($this->proxy_port)) {
            $this->_isproxy = true;
            $host           = $this->proxy_host;
            $port           = $this->proxy_port;
        } else {
            $host = $this->host;
            $port = $this->port;
        }

        $this->status = 0;

        if ($fp = fsockopen($host, $port, $errno, $errstr, $this->_fp_timeout)) {
            // socket connection succeeded

            return true;
        } else {
            // socket connection failed
            $this->status = $errno;
            switch ($errno) {
                case -3:
                    $this->error = 'socket creation failed (-3)';
                case -4:
                    $this->error = 'dns lookup failure (-4)';
                case -5:
                    $this->error = 'connection refused or timed out (-5)';
                default:
                    $this->error = 'connection failed (' . $errno . ')';
            }
            return false;
        }
    }

    /*======================================================================*\
        Function:	_disconnect
        Purpose:	disconnect a socket connection
        Input:		$fp	file pointer
    \*======================================================================*/

    /**
     * @param $fp
     * @return bool
     */
    public function _disconnect($fp)
    {
        return fclose($fp);
    }

    /*======================================================================*\
        Function:	_prepare_post_body
        Purpose:	Prepare post body according to encoding type
        Input:		$formvars  - form variables
                    $formfiles - form upload files
        Output:		post body
    \*======================================================================*/

    /**
     * @param $formvars
     * @param $formfiles
     * @return string|void
     */
    public function _prepare_post_body($formvars, $formfiles)
    {
        $formvars  = (array)$formvars;
        $formfiles = (array)$formfiles;

        if (0 == count($formvars) && 0 == count($formfiles)) {
            return;
        }

        switch ($this->_submit_type) {
            case 'application/x-www-form-urlencoded':
                reset($formvars);
                while (list($key, $val) = each($formvars)) {
                    if (is_array($val) || is_object($val)) {
                        while (list($cur_key, $cur_val) = each($val)) {
                            $postdata .= urlencode($key) . '[]=' . urlencode($cur_val) . '&';
                        }
                    } else {
                        $postdata .= urlencode($key) . '=' . urlencode($val) . '&';
                    }
                }
                break;

            case 'multipart/form-data':
                $this->_mime_boundary = 'Snoopy' . md5(uniqid(microtime(), true));

                reset($formvars);
                while (list($key, $val) = each($formvars)) {
                    if (is_array($val) || is_object($val)) {
                        while (list($cur_key, $cur_val) = each($val)) {
                            $postdata .= '--' . $this->_mime_boundary . "\r\n";
                            $postdata .= "Content-Disposition: form-data; name=\"$key\[\]\"\r\n\r\n";
                            $postdata .= "$cur_val\r\n";
                        }
                    } else {
                        $postdata .= '--' . $this->_mime_boundary . "\r\n";
                        $postdata .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
                        $postdata .= "$val\r\n";
                    }
                }

                reset($formfiles);
                while (list($field_name, $file_names) = each($formfiles)) {
                    $file_names = (array)$file_names;
                    while (list(, $file_name) = each($file_names)) {
                        if (!is_readable($file_name)) {
                            continue;
                        }

                        $fp           = fopen($file_name, 'r');
                        $file_content = fread($fp, filesize($file_name));
                        fclose($fp);
                        $base_name = basename($file_name);

                        $postdata .= '--' . $this->_mime_boundary . "\r\n";
                        $postdata .= "Content-Disposition: form-data; name=\"$field_name\"; filename=\"$base_name\"\r\n\r\n";
                        $postdata .= "$file_content\r\n";
                    }
                }
                $postdata .= '--' . $this->_mime_boundary . "--\r\n";
                break;
        }

        return $postdata;
    }
}

?>

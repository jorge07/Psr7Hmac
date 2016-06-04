<?php

namespace UMA\Psr\Http\Message\HMAC;

final class Specification
{
    /**
     * Name of the HTTP header that will hold the authentication
     * credentials (i.e. the HMAC signature). Must conform to RFC7235.
     *
     * @see http://tools.ietf.org/html/rfc7235#section-4.2
     */
    const AUTH_HEADER = 'Authorization';

    /**
     * Authentication credentials prefix. Its purpose is telling the
     * message receiver which kind of data the authentication header is holding.
     */
    const AUTH_PREFIX = 'HMAC-SHA256 ';

    /**
     * 'Authorization' header value definition. It must begin with 'HMAC-SHA256',
     * followed by a single whitespace and then a base64-encoded 32 byte sequence.
     *
     * @example Authorization: HMAC-SHA256 y0SLRAxCrIrQhPyKh5XJj1t4AjWcMF6r1X7Nsg4kiJY=
     */
    const AUTH_REGEXP = '@^HMAC-SHA256 ([+/0-9A-Za-z]{43}=)$@';

    /**
     * The 'Date' header field represents the date and time at which the
     * message was originated.
     *
     * The library client may choose to use this field to invalidate
     * withheld HTTP requests.
     *
     * @see https://tools.ietf.org/html/rfc7231#section-7.1.1.2
     */
    const DATE_HEADER = 'Date';

    /**
     * Hash algorithm used in conjunction with the HMAC function.
     */
    const HASH_ALGORITHM = 'sha256';

    /**
     * The 'Nonce' header field holds a random nonce. The library client
     * may choose to use this field to detect replayed HTTP requests.
     */
    const NONCE_HEADER = 'Nonce';

    /**
     * Name of the HTTP header that holds the list of signed headers.
     *
     * When verifying the authenticity on an HTTP message, any header
     * not included in that list must be stripped from the message
     * before attempting to serialize it.
     *
     * An HTTP message without the Signed-Headers header cannot
     * pass the HMAC verification.
     *
     * An HTTP message with a Signed-Headers header that is not covered
     * by the HMAC signature will neither pass the HMAC verification.
     *
     * The list itself consists of an alphanumerically, lowercase sorted sequence
     * of header names concatenated by commas.
     *
     * A valid Signed-Headers header must include its own header name, so at
     * the very least it will be the only header in the list.
     *
     * As per RFC 7230 Section 3.2 commas are not legal characters in a header name,
     * hence there cannot be any ambiguity when parsing the header value.
     *
     * @example Signed-Headers: api-key,content-type,host,signed-headers
     * @example Signed-Headers: signed-headers
     */
    const SIGN_HEADER = 'Signed-Headers';

    /**
     * 'Signed-Headers' header value definition. As explained above, its a
     * comma-separated list of lowercase header names.
     *
     * The allowed characters in a header name, defined in RFC 7230 Sections 3.2 and 3.2.6,
     * are pasted below for easier reference:
     *
     *  header-field   = field-name ":" OWS field-value OWS
     *  field-name     = token
     *  token          = 1*tchar
     *  tchar          = "!" / "#" / "$" / "%" / "&" / "'" / "*"
     *                 / "+" / "-" / "." / "^" / "_" / "`" / "|" / "~"
     *                 / DIGIT / ALPHA
     *  DIGIT          =  %x30-39           ; 0-9
     *  ALPHA          =  %x41-5A / %x61-7A ; A-Z / a-z
     *
     * @see http://tools.ietf.org/html/rfc5234#appendix-B.1
     * @see http://tools.ietf.org/html/rfc7230#section-3.2
     * @see http://tools.ietf.org/html/rfc7230#section-3.2.6
     */
    const SIGN_REGEXP = "@^[-!#$%&'*+.^_`|~0-9a-z]+(,[-!#$%&'*+.^_`|~0-9a-z]+)*$@";
}

<?php
class SPOAuthDataStore extends OAuthDataStore {
    private $consumer;
    private $request_token;
    private $access_token;
    private $nonce;

    function __construct() {
    }

    function __destruct() {
    }

    /**
     * Check if consumer exists from a given consumer key.
     *
     * @param $consumer_key
     *   String. The consumer key.
     */
    function lookup_consumer($consumer_key) {
        if ($consumer_key == CONSUMER_KEY)
            return new OAuthConsumer(CONSUMER_KEY, CONSUMER_SECRET);
        else
            throw new OAuthException('invalid_consumer');
    }

    /**
     * Check if the token exists.
     *
     * @param $consumer
     *   Object. The service consumer information.
     * @param $token_type
     *   Strint. The type of the token: 'request' or 'access'.
     * @param $token
     *   Strint. The token value.
     * @return
     *   String or NULL. The existing token or NULL in
     *   case it doesnt exist.
     */
    function lookup_token($consumer, $token_type, $token) {
        return false;
    }

    /**
     * Check if the nonce value exists. If not, generate one.
     *
     * @param $consumer
     *   Object. The service consumer information with both key
     *   and secret values.
     * @param $token
     *   Strint. The current token.
     * @param $nonce
     *   Strint. A new nonce value, in case a one doesnt current exit.
     * @param $timestamp
     *   Number. The current time.
     * @return
     *   String or NULL. The existing nonce value or NULL in
     *   case it doesnt exist.
     */
    function lookup_nonce($consumer, $token, $nonce, $timestamp) {
        return false;
    }

    /**
     * Generate a new request token.
     *
     * @param $consumer
     *   Object. The service consumer information.
     */
    function new_request_token($consumer, $callback = null) {
        return false;
    }

    function new_access_token($token_old, $consumer, $verifier = null) {
        return false;
    }

}

?>
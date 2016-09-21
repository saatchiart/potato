<?php

namespace Demand\Potato;


class SessionHandler implements \SessionHandlerInterface
{
    /** @var Client */
    private $client = null;

    /**
     * The prefix to be used in Couchbase keynames.
     */
    protected $keyPrefix = 'cbsession:';

    /**
     * Define a expiration time in seconds.
     */
    protected $expire;

    /**
     * Set the default configuration params on init.
     */
    public function __construct(Client $client, int $expire = 600) {
        $this->client = $client;
        $this->expire = $expire;
    }

    /**
     * Called by PHP on `session_start()`; make sure we have our
     * client defined.
     *
     * @param string $savePath
     * @param string $sessionName
     * @return bool
     */
    public function open($savePath, $sessionName)
    {
        return $this->client ? true : false;
    }

    /**
     * Close the connection. Called by PHP when the script ends.
     * Just return true, since we do not want to close our bucket connection.
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read data from the session.
     *
     * @param string $sessionId
     * @return mixed
     */
    public function read($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;
        $result = $this->client->getBucket()->get($key);

        return $result ?: null;
    }

    /**
     * Write data to the session
     */
    public function write($sessionId, $data)
    {
        $key = $this->keyPrefix . $sessionId;
        if(empty($data)) {
            return false;
        }

        $result = $this->client->getBucket()->upsert($key, $data, array('expiry' => $this->expire));
        return $result ? true : false;
    }

    /**
     * Delete data from the session.
     */
    public function destroy($sessionId)
    {
        $key = $this->keyPrefix . $sessionId;
        $result = $this->client->getBucket()->remove($key);

        return $result ? true : false;
    }

    /**
     * No need to garbage collect since CB does it for us automatically.
     *
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }

}
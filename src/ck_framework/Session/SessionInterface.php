<?php
namespace ck_framework\Session;

interface SessionInterface
{
    /**
     * get var in session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * add var in session
     *
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value): void;

    /**
     * delete var in session
     * @param string $key
     */
    public function delete(string $key): void;
}

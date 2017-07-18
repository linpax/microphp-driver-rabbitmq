<?php
/**
 * @link https://github.com/linpax/microphp-driver-rabbitmq
 * @copyright Copyright &copy; 2017 Linpax
 * @license https://github.com/linpax/microphp-driver-rabbitmq/blob/master/LICENSE
 */

namespace Micro\Driver\RabbitMQ;


class RabbitMQ
{
    /** @var \AMQPConnection $connect Connect to broker */
    protected $connect;
    /** @var \AMQPChannel $channel Channel of connection */
    protected $channel;

    protected $exchange;


    /**
     * Constructor RabbitMQ
     *
     * @access public
     *
     * @param array $params connect to broker
     *
     * @result void
     * @throws \AMQPConnectionException
     */
    public function __construct(array $params = [])
    {
        $this->connect = new \AMQPConnection($params);
        $this->connect->connect();

        $this->channel = new \AMQPChannel($this->connect);

        $this->exchange = new \AMQPExchange($this->channel);
        $this->exchange->setName($params['table']);
    }

    /**
     * Close RabbitMQ
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->connect->disconnect();
    }

    public function send($name='', array $params=[], $stream = 'sync')
    {
        return $this->exchange->publish(json_encode($params), $name);
    }
}
<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Exception;

abstract class JsonException extends Exception
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var string
     */
    protected $source;

    /**
     * @param @string $message
     *
     * @return void
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Get the status.
     *
     * @return int
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * Get the source.
     *
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Return the Exception as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'     => $this->id,
            'status' => $this->status,
            'title'  => $this->title,
            'detail' => $this->detail,
        ];
    }

    /**
     * Build the Exception.
     *
     * @param array $args
     *
     * @return string
     */
    protected function build(array $args)
    {
        /* TO-DO: I used a simple example, it needs validations/type checks before rendering since thigns may be optional now */
        $this->id = array_shift($args);

        $error = config(sprintf('errors.%s', $this->id));

        $this->title = $error['title'];
        $this->detail = vsprintf($error['detail'], $args);
        // to-do: could be better but its been 14+ hours...
        if (isset($error['source'])) {
            $this->source =
          isset($args[1])
             ? [$args[0] => vsprintf($error['source'], $args[1])]
             : ['parameter' => vsprintf($error['source'], $args)];
        }

        return $this->detail;
    }
}

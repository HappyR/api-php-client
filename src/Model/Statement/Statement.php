<?php

namespace Happyr\ApiClient\Model\Statement;

use Happyr\ApiClient\Model\CreatableFromArray;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Statement implements CreatableFromArray
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $sentence;

    /**
     * @var array
     */
    private $assessments;

    /**
     * @var int
     */
    private $progress;

    /**
     * @var string
     */
    private $postUrl;

    /**
     * @var string
     */
    private $fullPostUrl;

    /**
     * @var string
     */
    private $section;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     *
     * @return
     */
    public static function createFromArray(array $data)
    {
        $data = $data['data'];
        $statement = new self($data['id']);
        $statement->setSentence($data['sentence']);
        $statement->setAssessments($data['assessments']);
        $statement->setProgress($data['progress']);
        $statement->setPostUrl($data['post_url']);
        $statement->setFullPostUrl($data['full_post_url']);
        $statement->setSection($data['section']);
        $statement->setType($data['type']);

        return $statement;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * @param string $sentence
     */
    private function setSentence($sentence)
    {
        $this->sentence = $sentence;
    }

    /**
     * @return array
     */
    public function getAssessments()
    {
        return $this->assessments;
    }

    /**
     * @param array $assessments
     */
    private function setAssessments($assessments)
    {
        $this->assessments = $assessments;
    }

    /**
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     */
    private function setProgress($progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return string
     */
    public function getPostUrl()
    {
        return $this->postUrl;
    }

    /**
     * @param string $postUrl
     */
    private function setPostUrl($postUrl)
    {
        $this->postUrl = $postUrl;
    }

    /**
     * @return string
     */
    public function getFullPostUrl()
    {
        return $this->fullPostUrl;
    }

    /**
     * @param string $fullPostUrl
     */
    private function setFullPostUrl($fullPostUrl)
    {
        $this->fullPostUrl = $fullPostUrl;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    private function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    private function setType($type)
    {
        $this->type = $type;
    }
}

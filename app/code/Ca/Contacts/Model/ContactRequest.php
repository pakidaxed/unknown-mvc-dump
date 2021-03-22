<?php

namespace Ca\Contacts\Model;

use Ca\Framework\Helper\SqlBuilder;
use Ca\Framework\Helper\Validation;

class ContactRequest
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $subjectOptional;
    private $subject;
    private $message;
    private $seen = false;
    private $response;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getSubjectOptional()
    {
        return $this->subjectOptional;
    }

    /**
     * @param mixed $subjectOptional
     */
    public function setSubjectOptional($subjectOptional): void
    {
        $this->subjectOptional = $subjectOptional;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     */
    public function setSeen(bool $seen): void
    {
        $this->seen = $seen;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }


    public function load($id)
    {
        $db = new SqlBuilder();
        $contactRequest = $db->select()->from('requests')->where('id', Validation::validInteger($id))->getOne();
        $this->id = $contactRequest['id'];
        $this->firstName = $contactRequest['first_name'];
        $this->lastName = $contactRequest['last_name'];
        $this->email = $contactRequest['email'];
        $this->phone = $contactRequest['phone'];
        $this->subjectOptional = $contactRequest['subject_optional'];
        $this->subject = $contactRequest['subject'];
        $this->message = $contactRequest['message'];
        $this->response = $contactRequest['response'];
        $this->seen = $contactRequest['seen'];
        return $this;
    }

    public function save()
    {
        if ($this->id) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create()
    {
        $values = [
            'first_name' => Validation::validString($this->firstName),
            'last_name' => Validation::validString($this->lastName),
            'email' => Validation::validEmail($this->email),
            'phone' => $this->phone,
            'subject_optional' => Validation::validString($this->subjectOptional),
            'subject' => Validation::validString($this->subject),
            'message' => Validation::validString($this->message),
            'seen' => 0
        ];
        $db = new SqlBuilder();
        $db->insert('requests')->values($values)->exec();
    }

    public function update()
    {
        $values = [
            'response' => Validation::validString($this->response),
            'seen' => true,
        ];

        $db = new SqlBuilder();
        $db->update('requests')->set($values)->where('id', $this->id)->exec();
    }

    public function delete()
    {
        $db = new SqlBuilder();
        $db->delete()->from('requests')->where('id', $this->id)->exec();
    }

    public function getAllContactRequests()
    {
        $db = new SqlBuilder();
        return $db->select()->from('requests')->get();
    }

}
<?php

namespace Ca\Contacts\Controller;

use Ca\Contacts\Model\ContactRequest;
use Ca\Framework\Core\Controller;
use Ca\Framework\Helper\FormBuilder;
use Ca\Framework\Helper\Request;


class Index extends Controller
{
    private $post;

    public function __construct()
    {
        $request = new Request();
        $this->post = $request->getPost();
        parent::__construct('Ca/Contacts');
    }

    public function index()
    {
        $contactCollection = new ContactRequest();
        $data['contactRequests'] = $contactCollection->getAllContactRequests();
        $this->render('admin/list', $data);
    }


    public function answer($id)
    {
        $contactRequest = new ContactRequest();
        $contactRequest = $contactRequest->load($id);

        $contactRequest->setResponse($this->post['response']);
        $contactRequest->update();

        header("Location: /contacts");
    }


    public function create()
    {
        $form = new FormBuilder('POST', '/contacts/store', '', '');
        $form->input('text', 'first_name', 'input-text', 'first_name', 'First name')
            ->input('text', 'last_name', 'input-text', 'last_name', 'Last name')
            ->input('email', 'email', 'input-text', 'email', 'Email address')
            ->input('text', 'phone', 'input-text', 'phone', 'Phone (optional)')
            ->select(
                'subject_optional',
                ['garantinis' => 'Garantinis aptarnavimas', 'produktai' => 'Apie produktus', 'kita' => 'Kita'],
                '')
            ->input('text', 'subject', 'input-text', 'subject', 'Subject')
            ->textarea('message', 'Message')
            ->button('send', 'Send');

        $data['form'] = $form->get();
        $this->render('form/create', $data);
    }

    public function store()
    {
        $contactRequest = new ContactRequest();
        $contactRequest->setFirstName($this->post['first_name']);
        $contactRequest->setLastName($this->post['last_name']);
        $contactRequest->setEmail($this->post['email']);
        $contactRequest->setPhone($this->post['phone']);
        $contactRequest->setSubjectOptional($this->post['subject_optional']);
        $contactRequest->setSubject($this->post['subject']);
        $contactRequest->setMessage($this->post['message']);
        $contactRequest->setSeen(0);
        $contactRequest->save();

        header("Location: /contacts");
    }

    public function remove()
    {
        $id = $this->post['id'];
        $product = new ContactRequest();
        $product->load($id)->delete();

        header("Location: /contacts");
    }

    public function show($id)
    {
        $contactRequest = new ContactRequest();
        $contactRequest = $contactRequest->load($id);

        if (!$contactRequest->isSeen()) {
            $contactRequest->setSeen(true);
            $contactRequest->save();
        }

        $form = new FormBuilder('POST', '/contacts/answer/' . $contactRequest->getId(), '', '');
        $form->input('text', 'response', 'input-text', 'response', 'Atsakymas', '')
            ->button('save', 'save');

        $data['contactRequest'] = $contactRequest;
        $data['form'] = $form->get();
        $this->render('admin/show', $data);

    }

}
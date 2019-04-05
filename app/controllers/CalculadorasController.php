<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CalculadorasController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for calculadoras
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Calculadoras', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $calculadoras = Calculadoras::find($parameters);
        if (count($calculadoras) == 0) {
            $this->flash->notice("The search did not find any calculadoras");

            $this->dispatcher->forward([
                "controller" => "calculadoras",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $calculadoras,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a calculadora
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $calculadora = Calculadoras::findFirstByid($id);
            if (!$calculadora) {
                $this->flash->error("calculadora was not found");

                $this->dispatcher->forward([
                    'controller' => "calculadoras",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $calculadora->id;

            $this->tag->setDefault("id", $calculadora->id);
            $this->tag->setDefault("nombre", $calculadora->nombre);
            $this->tag->setDefault("descripcion", $calculadora->descripcion);
            $this->tag->setDefault("created", $calculadora->created);
            
        }
    }

    /**
     * Creates a new calculadora
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'index'
            ]);

            return;
        }

        $calculadora = new Calculadoras();
        $calculadora->nombre = $this->request->getPost("nombre");
        $calculadora->descripcion = $this->request->getPost("descripcion");
        $calculadora->created = $this->request->getPost("created");
        

        if (!$calculadora->save()) {
            foreach ($calculadora->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("calculadora was created successfully");

        $this->dispatcher->forward([
            'controller' => "calculadoras",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a calculadora edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $calculadora = Calculadoras::findFirstByid($id);

        if (!$calculadora) {
            $this->flash->error("calculadora does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'index'
            ]);

            return;
        }

        $calculadora->nombre = $this->request->getPost("nombre");
        $calculadora->descripcion = $this->request->getPost("descripcion");
        $calculadora->created = $this->request->getPost("created");
        

        if (!$calculadora->save()) {

            foreach ($calculadora->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'edit',
                'params' => [$calculadora->id]
            ]);

            return;
        }

        $this->flash->success("calculadora was updated successfully");

        $this->dispatcher->forward([
            'controller' => "calculadoras",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a calculadora
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $calculadora = Calculadoras::findFirstByid($id);
        if (!$calculadora) {
            $this->flash->error("calculadora was not found");

            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'index'
            ]);

            return;
        }

        if (!$calculadora->delete()) {

            foreach ($calculadora->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "calculadoras",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("calculadora was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "calculadoras",
            'action' => "index"
        ]);
    }

}

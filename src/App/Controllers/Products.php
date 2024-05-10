<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;
use Framework\Exceptions\PageNotFoundException;

class Products
{
    public function index()
    {
        // create new model object
        $model = new Product;

        // call getData() method from product model
        // assign result set to $products variable
        $products = $model->getData();

        if ($products === false) {

            throw new PageNotFoundException("Product not found");
        }
        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Products"
        ]);

        // pass db result set to view in array named "products"
        echo $viewer->render("Products/index.php", [
            "products" => $products
        ]);
    }

    public function show(string $id = NULL)
    {
        $model = new Product;

        $product = $model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
        }
        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Product"
        ]);

        // pass db result set to view in array named "product"
        echo $viewer->render("Products/show.php", [
            "product" => $product
        ]);
    }

    public function edit(string $id = NULL)
    {
        $model = new Product;

        $product = $model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
        }

        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Edit Product"
        ]);

        echo $viewer->render("Products/edit.php", [
            "product" => $product
        ]);
    }

    public function new()
    {
        // display empty form for new product
        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "New Product"
        ]);

        echo $viewer->render("Products/new.php");
    }

    public function create()
    {
        // create a data array to hold form field data
        $data = [
            "name" => $_POST["name"],
            "description" => $_POST["description"]
        ];

        $model = new Product;

        // capture the last ID inserted into the db for a new record
        $insertID = $model->insert($data);

        // used to check successful database insert of new record
        if ($insertID) {

            // use insertID to redirect the user to the product page for the new product
            header("Location: " . WEB_ROOT . "products/{$insertID}/show");
            exit;
        } else {

            // default form view for form with errors displayed
            $viewer = new Viewer;

            echo $viewer->render("shared/header.php", [
                "title" => "New Product"
            ]);

            echo $viewer->render("Products/new.php", [
                "errors" => $model->getErrors()
            ]);
        }
    }
    public function update(string $id)
    {
        $model = new Product;

        // gets data from database for default form
        $product = $model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
        }

        // replaces database info for sticky forms
        $product["name"] = $_POST["name"];
        $product["description"] = empty($_POST["description"]) ? null : $_POST["description"];

        if ($model->update($id, $product)) {

            header("Location: " . WEB_ROOT . "products/{$id}/show");
            exit;
        } else {

            $viewer = new Viewer;

            echo $viewer->render("shared/header.php", [
                "title" => "Edit Product"
            ]);

            echo $viewer->render("Products/edit.php", [
                "errors" => $model->getErrors(),
                "product" => $product
            ]);
        }
    }
    public function delete(string $id)
    {
        $model = new Product;

        $product = $model->find($id);

        // this if block checks the request method of the delete request
        // if the request method is post, the query will be executed
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $model->delete($id);

            header("Location: " . WEB_ROOT . "products/index");
            exit;
        }

        // this block displays the initial delete verification page
        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Delete Product"
        ]);

        echo $viewer->render("Products/delete.php", [
            "product" => $product
        ]);
    }
}

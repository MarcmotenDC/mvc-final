
<h1>New Product</h1>

<form action="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/create" method="post">

<!-- include the form contents from the new form.php -->
<?php require "form.php" ?>

</form>

</body>
</html>
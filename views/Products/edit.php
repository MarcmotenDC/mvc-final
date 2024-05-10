
<h1>Edit Product Page</h1>

<form action="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/update" method="post">

<!-- include the form contents from the new form.php -->
<?php require "form.php" ?>

</form>

<p><a href="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/show">Cancel</a></p>

</body>
</html>
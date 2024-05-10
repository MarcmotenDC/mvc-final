
<h1>Delete Product</h1>

<form action="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/delete" method="post">

<p>Are you sure you want to delete this product?</p>

<button>Yes</button>

</form>

<p><a href="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/show">Cancel</a></p>
</body>
</html>
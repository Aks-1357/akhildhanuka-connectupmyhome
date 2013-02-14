<?php foreach ($this->_data as $this->comment): ?>
	<?php if ($this->comment['itemid'] != $this->onlyitem) continue; ?>
	<?php include $this->tmplpath . DS .'comment.php' ?>
<?php endforeach; ?>


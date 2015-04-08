<!DOCTYPE html>
<html>
    <head>
        
        
        <title>Admin - Blog</title>
    </head>
    <body>
        <div id="content">
            
    <form method="post">
        
        <?php echo $form->label('login'); ?>
        <?php echo $form->render('login'); ?>
        
        <?php echo $form->label('email'); ?>
        <?php echo $form->render('email'); ?>
        
        <?php echo $form->label('password'); ?>
        <?php echo $form->render('password'); ?>
        <?php echo $form->render('confirmPassword'); ?>

        
        <input type="hidden" name="<?php echo $this->security->getTokenKey(); ?>" value="<?php echo $this->security->getToken(); ?>" />

        <?php echo $form->render('submit'); ?>
    </form>

        </div>

        <div id="footer">
            
        </div>
    </body>
</html>
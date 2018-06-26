<!--page configuration-->   
<?php $title = "configuration"?>
<?php ob_start(); ?>

<section>
<div class="container">
        <?php include('body/connexion.php')?>
        <h1 class="mt-4 text-secondary">Inscription au site <span class="text-info"> B.A TV Bande Annonce</span></h1>
        <p>Nous sommes heureux de vous compter comme nouvelle utilisateur <i class="far fa-smile text-warning"></i></p>
        <div class="row">
            <div class="col-12 col-lg-6">
                <script>
                    function redirect(){
                        setTimeout(function(){
                            window.location = 'index.php?page=home'; 
                             }, 4000);
                    }
                </script>
                <?php
                    /*isset type submit fom form add admin*/
                    if(isset($_POST['submitMod'])){

                        $loginSubmit=new PageConfig;
                        $result=$loginSubmit->is_Email($_POST['email']);

                        $name=htmlspecialchars(trim($_POST['name']));
                        $pseudo=htmlspecialchars(trim($_POST['pseudo']));
                        $email=$_POST['email'];
                        $password=$_POST['password'];
                        $hash = $result['password'];
                        $isPasswordCorrect = password_verify($password, $hash);
                        $errors=array();

                        if(empty($name) OR empty($pseudo)){
                            $errors['empty']="Tous les champs ne sont pas remplis.";
                        }

                        if(isset($_FILES['avatar']) AND $_FILES['avatar']['error'] == 0){
                            
                            if ($_FILES['avatar']['size'] <= 4000000){
        
                                $infosfichier = pathinfo($_FILES['avatar']['name']);
                                $extension_upload = $infosfichier['extension'];
                                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                                
                                if (in_array($extension_upload, $extensions_autorisees)){
                                    move_uploaded_file($_FILES['avatar']['tmp_name'], 'public/images/users/' . basename($pseudo.$_FILES['avatar']['name']));
                                    $image=basename($pseudo.$_FILES['avatar']['name']);
                                } 
                                else{
                                    $errors['format'] = "Format de fichier non autorisé";
                                }   
                            }
                            else{
                                $errors['size'] = "Image qui dépasse la taille autorisé";
                            }
                        }
                        else{
                            $image = $_SESSION['user']['image']  ;   
                        }

                        if(!$isPasswordCorrect){
                            $errors['mdp'] = "mauvais mot de passe";
                        }

                        if(!empty($errors)){
                            ?>
                            <div class="alert alert-danger"> 
                            <?php
                                foreach($errors as $error){
                                    echo "<strong>"."ATTENTION!!! "."</strong>". $error."<br/>";
                                }
                            ?>
                            </div>
                            <?php
                        }
                        else{
                            $update = new PageConfig;
                            $update -> update_User($name,$pseudo,$image,$email);
                            ?>
                            <div class="alert alert-success"> 
                                Pour que vos modifications soit pris en compte il faut vous deconnecter !!
                            </div>
                            <script>
                                redirect();
                            </script>
                            <?php
                        }
                    }         
                ?>        
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group ">
                                <label for="name"><i class="fas fa-user bg-danger text-white p-2"></i></label>
                                <input type="text" name="name" id="name" placeholder="Nom, prénom" value="<?= $_SESSION['user']['name']   ?>" class="form-control formIns py-2"/>
                            </div>
                            <div class="form-group  ">
                                <label for="name"><i class="fas fa-user-tie bg-danger text-white p-2"></i></label>
                                <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" value="<?= $_SESSION['user']['pseudo']   ?>" class="form-control formIns py-2"/>
                            </div>
                            <div class="form-group  ">
                                <label for="email"><i class="fas fa-envelope bg-danger text-white p-2"></i></label>
                                <input type="email"  placeholder="Email" value="<?= $_SESSION['user']['email']   ?>" disabled="disabled" class="form-control formIns py-2"/>
                            </div>
                            <input type="hidden" name="email" value="<?= $_SESSION['user']['email']   ?>">
                            <div class="form-group ">
                                <input type="file" class="btn btn-dark" name="avatar" id="avatar">
                            </div>
                            <div class="form-group  ">
                                <label for="password"><i class="fas fa-key bg-danger text-white p-2"></i></label>
                                <input type="password" name="password" id="password" placeholder="Saisisser votre mot de passe" class="form-control formIns py-2"/>
                            </div>
                            <div class="mb-4 d-flex justify-content-center ">
                                <button type="submit" name="submitMod" class="px-5 btn btn-dark">Modifier</button>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="col-6 d-none d-lg-block">
                <img src="public/images/Ba-ins.jpg" class="mt-5 ml-5" alt="">
            </div>
        </div>
    </div>
</section>


<?php $content = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>

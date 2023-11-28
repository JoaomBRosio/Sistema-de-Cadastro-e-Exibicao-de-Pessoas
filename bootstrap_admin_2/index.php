<?php
  $pdo = new PDO('mysql:host=localhost;dbname=bootstrap_projeto','root','');
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de controle</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-fixed-top navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">AAANO Cadastro de Membros</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul id="menu-principal" class="nav navbar-nav">
            <li><a ref_sys="cadastrar_membros" href="#about">Cadastrar membro</a></li>
            <li><a ref_sys="lista_membros" href="#contact">Lista de membros</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div style="position: relative;top:50px;" class="box">
    <header id="header">
      <div class="container">
          <div class="row">
              <div class="col-md-9">
                <h2><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Painel de controle</h2>
              </div>
              <div class="col-md-3">
              </div>
          </div>
      </div>
    </header>
    <section class="bread">
      <div class="container">
        <ol class="breadcrumb">
          <li class="active">Home</li>
        </ol>
      </div>
    </section>
    <section class="principal">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                  <div class="list-group">
                      <a href="#" class="list-group-item" ref_sys="cadastrar_membros"><span class="glyphicon glyphicon-pencil"></span>Cadastrar membros</a>
                   <a href="#" class="list-group-item" ref_sys="lista_membros">
                     <span class="glyphicon glyphicon-list-alt"></span> Lista de membros</a>
                  </div>
                </div>
                <div class="col-md-9">
                <?php
                //if de verificação de dados na realização do post
                  if(isset($_POST['cadastrar_membros'])){
                    $nome = $_POST['nome_membro'];
                    $descricao = $_POST['descricao'];
                    $sql = $pdo->prepare("INSERT INTO `tb_membros` VALUES (null,?,?)");
                    $sql->execute(array($nome,$descricao));
                    echo "<script language='javascript' type='text/javascript'>alert('Usuário cadastrado com sucesso');</script>";
                  }
                ?>
                        <div id="cadastrar_membros_section" class="panel panel-default">
                          <div class="panel-heading cor-padrao">
                            <h3 class="panel-title">Cadastrar membro:</h3>
                          </div>
                          <div class="panel-body">
                            <form method="post">
                               <div class="form-group">
                                <label for="email">Nome do membro:</label>
                                <input type="text" name="nome_membro" class="form-control" />
                              </div>
                              <div class="form-group">
                                <label for="email">Descrição do membro:</label>
                                <textarea name="descricao" style="height: 140px;resize: vertical;" class="form-control"></textarea>
                              </div>
                              <input type="hidden" name="cadastrar_membros" />
                              <button type="submit" class="btn btn-default">Enviar!</button>
                            </form>
                          </div>
                  </div> 
                      <div id="lista_membros_section" class="panel panel-default">
                          <div class="panel-heading cor-padrao">
                            <h3 class="panel-title">Membros cadastrados:</h3>
                          </div>
                          <div class="panel-body">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th>ID:</th>
                                  <th>Nome do membro</th>
                                  <th>Descrição do membro</th>
                                  <th>#</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                  $selecionarMembros = $pdo->prepare("SELECT `id`,`nome`,`descricao` FROM `tb_membros`");
                                  $selecionarMembros->execute();
                                  $membros = $selecionarMembros->fetchAll();
                                  foreach($membros as $key=>$value){
                                ?>
                                <tr>
                                  <td><?php echo $value['id']; ?></td>
                                  <td><?php echo $value['nome'] ?></td>
                                  <td><?php echo $value['descricao'] ?></td>
                                  <td><button id_membro="<?php echo $value['id']; ?>" type="button" class="deletar-membro btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> Excluir</button></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>
                  </div> 
                </div>
            </div>
        </div>
    </section>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(function(){
        cliqueMenu();
        scrollItem();
          function cliqueMenu(){
              $('#menu-principal a, .list-group a').click(function(){
                 $('.list-group a').removeClass('active').removeClass('cor-padrao');
                 $('#menu-principal a').parent().removeClass('active');
                $('#menu-principal a[ref_sys='+$(this).attr('ref_sys')+']').parent().addClass('active');
                $('.list-group a[ref_sys='+$(this).attr('ref_sys')+']').addClass('active').addClass('cor-padrao');
                return false;
              })
          }
          function scrollItem(){
               $('#menu-principal a, .list-group a').click(function(){
                    var ref = '#'+$(this).attr('ref_sys')+'_section';
                    var offset = $(ref).offset().top;
                    $('html,body').animate({'scrollTop':offset-50});
                    if($(window)[0].innerWidth <= 768){
                    $('.icon-bar').click();
                    }
              });
          }
          $('button.deletar-membro').click(function(){
              var id_membro = $(this).attr('id_membro');
              var el = $(this).parent().parent();
              $.ajax({
                  method:'post',
                  data:{'id_membro':id_membro},
                  url:'deletar.php'
              }).done(function(){
                el.fadeOut(function(){
                 el.remove();
              });
              })
          })
      })
    </script>
  </body>
</html>
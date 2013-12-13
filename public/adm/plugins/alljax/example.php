<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <script src="/adm/scripts/jquery-1.8.2.min.js"></script>
    <script src="alljax.js"></script>
    <script>
      $(document).ready(function(){
        $('.replace_container').alljax('#main',{
          usePushState: 'safe',
          recursiveEvent: 'true',
          before: function(){
            //console.log('show loading');
          },
          error: function(data){
            //console.log('ERROR: ' + data.error);
          },
          after: function(data){
            //console.log('exec: ' + data.exec);
            //console.log('hide loading');
            $('.replace_container').click(function(){
              console.log('vagina');
            });
          }
        });
        
        $('#main').css('text-align','center');
        $('#button').width(100);
        $('#button').click(function(){
          alert('Button clicked!');
        });
      });
    </script>

    <style>
      div {border: solid 1px #000}
      #main {background-color: yellow}
    </style>
  </head>
  <body>
    <div id="layout">
      HERE IS THE LAYOUT<br />
      <h2>Menu</h2>
      <ul>
        <li><a href='conteudo.php?page=<?=(intval($_GET['page'])+1)?>' class="replace_container">Explore</a></li>
      </ul>
      BELOW IS THE MAIN CONTAINER:<br />
      <div id="main">
        Home Contents<br />
        <button id="button">Button <?=$_GET['page']?></button>
      </div>
      
    </div>
  </body>
</html>

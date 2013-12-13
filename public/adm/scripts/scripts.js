//function Amigavel(s) {
//  s = retira_acentos(s);
//  var url = s.toLowerCase()// change everything to lowercase
//          .replace(/^\s+|\s+$/g, "")// trim leading and trailing spaces
//          .replace(/[_|\s]+/g, "-")// change all spaces and underscores to a hyphen
//          .replace(/[^a-z0-9-]+/g, "")// remove all non-alphanumeric characters except the hyphen
//          .replace(/[-]+/g, "-")// replace multiple instances of the hyphen with a single instance
//          .replace(/^-+|-+$/g, "")// trim leading and trailing hyphens
//          ;
//
//  return url;
//}
//
//function retira_acentos(palavra) {
//  var com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ';
//  var sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC';
//  var nova = '';
//  for (i = 0; i < palavra.length; i++) {
//    if (com_acento.search(palavra.substr(i, 1)) >= 0) {
//      nova += sem_acento.substr(com_acento.search(palavra.substr(i, 1)), 1);
//    } else {
//      nova += palavra.substr(i, 1);
//    }
//  }
//  return nova;
//}
//
function show_salvo()
{
  $('#alert_salvo').fadeIn(300);
}
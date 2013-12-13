    function BrowseServer( startupPath, functionData )
    {
      // You can use the "CKFinder" class to render CKFinder in a page:
      var finder = new CKFinder();

      // The path for the installation of CKFinder (default = "/ckfinder/").
      finder.basePath = '/adm/plugins/ckfinder23/';

      //Startup path in a form: "Type:/path/to/directory/"
      finder.startupPath = startupPath;

      // Name of a function which is called when a file is selected in CKFinder.
      finder.selectActionFunction = SetFileField;

      // Additional data to be passed to the selectActionFunction in a second argument.
      // We'll use this feature to pass the Id of a field that will be updated.
      finder.selectActionData = functionData;

      // Launch CKFinder
      finder.popup();
    }

    // This is a sample function which is called when a file is selected in CKFinder.
    function SetFileField( fileUrl, data )
    {
      document.getElementById( data["selectActionData"] ).value = fileUrl;
    }

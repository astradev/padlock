
  $.cookie.defaults.path = '{{@BASE}}/';
  function updateTree() {
    $.ajaxSetup ({
      cache: false
    });
    var ajax_load = "<img style='margin-left:35%;' src='{{@BASE}}/ui/img/reload.gif' alt='loading...' />";
    $.removeCookie('padlock_tree');
    $( "#tree" ).html( ajax_load );
    $.ajax({
      url: '{{@BASE}}/api/foldertree',
      type: "GET",
      success: function(data){
        $('#tree').html( data );
        $('#tree').easytree({
          data: $.cookie('padlock_tree'),
          stateChanged: stateChanged
        });
        console.log( 'Tree was updated' );
      },
      error: function() { console.log('Could not get foldertree'); }
    });
  }
  $('[rel="tooltip"]').tooltip();

  setTimeout(function(){
	  $(".droptext").fadeOut();
  }, 5000);

  function stateChanged(nodes, nodesJson) {
    var t = nodes[0].text;
    $.cookie('padlock_tree', nodesJson, { path: '{{@BASE}}/' } );
  }

  var easyTree = $('#tree').easytree({
    data: $.cookie('padlock_tree'),
    stateChanged: stateChanged,
    enableDnd: true
  });

  function openAllNodes() {
    var nodes = easyTree.getAllNodes();
    toggleNodes(nodes, 'open');
    easyTree.rebuildTree(nodes);
  }

  function closeAllNodes() {
    var nodes = easyTree.getAllNodes();
    toggleNodes(nodes, 'close');
    easyTree.rebuildTree(nodes);
  }

  function toggleNodes(nodes, openOrClose){
    var i = 0;
    for (i = 0; i < nodes.length; i++) {
      nodes[i].isExpanded = openOrClose == "open"; // either expand node or don't

      // if has children open/close those as well
      if (nodes[i].children && nodes[i].children.length > 0) {
        toggleNodes(nodes[i].children, openOrClose);
      }
    }
  }

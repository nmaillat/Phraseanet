

;(function(window){

  var Feedback = function($container, context){
    this.container = $($container);

    this.Context = context;

    this.selection = new Selectable(
      $('.user_content .badges', this.container),
      {
        selector:'.badge'
      }
    );

    var $this = this;

    $('.content .options .select-all', this.container).bind('click', function(){
      $this.selection.selectAll();
    });
    $('.content .options .unselect-all', this.container).bind('click', function(){
      $this.selection.empty();
    });


    $('a.user_adder', this.container).bind('click', function(){

      var $this = $(this);

      $.ajax({
        type: "GET",
        url: $this.attr('href'),
        dataType: 'html',
        beforeSend:function(){
          if($('#user_adder_dialog').length == 0)
          {
            $('body').append('<div id="user_adder_dialog" style="display:none;"></div>');
          }
          $('#user_adder_dialog').addClass('loading').empty().dialog({
              buttons:{},
              draggable:false,
              resizable:false,
              closeOnEscape:true,
              modal:true,
              width:'400',
              height:'400'
          }).dialog( "moveToTop" );
        },
        success: function(data){
          $('#user_adder_dialog').removeClass('loading').empty().append(data);
          return;
        },
        error: function(){
          $('#user_adder_dialog').dialog('destroy');
          $('#user_adder_dialog').remove();
          return;
        },
        timeout: function(){
          $('#user_adder_dialog').dialog('destroy');
          $('#user_adder_dialog').remove();
          return;
        }
      });

      return false;
    });

    $('#PushBox form[name="FeedBackForm"]').bind('submit', function(){

      var $this = $(this);

      $.ajax({
        type: $this.attr('method'),
        url: $this.attr('action'),
        dataType: 'json',
        data: $this.serializeArray(),
        beforeSend:function(){

        },
        success: function(data){
          if(data.success)
          {
            humane.info(data.message);
            p4.Dialog.Close(1);
            p4.WorkZone.refresh();
          }
          else
          {
            humane.error(data.message);
          }
          return;
        },
        error: function(){

          return;
        },
        timeout: function(){

          return;
        }
      });

      return false;
    });

    $('.FeedbackSend', this.container).bind('click', function(){
          if($('.badges .badge', $container).length === 0)
          {
            alert(language.FeedBackNoUsersSelected);
            return;
          }

          var buttons = {};

          buttons[language.send] = function(){
            $dialog.Close();

            $('input[name="name"]', $FeedBackForm).val($('input[name="name"]', $dialog.getDomElement()).val());
            $('textarea[name="message"]', $FeedBackForm).val($('textarea[name="message"]', $dialog.getDomElement()).val());

            $FeedBackForm.trigger('submit');
          };

          var options = {
            size : 'Small',
            buttons : buttons,
            loading : true,
            title : language.send,
            closeOnEscape : true,
            cancelButton : true
          };

          var $dialog = p4.Dialog.Create(options, 2);

          var $FeedBackForm = $('form[name="FeedBackForm"]', $container);

          var callback = function(rendered){

            $('input[name="name"]', rendered).val($('input[name="name"]', $FeedBackForm).val());
            $('textarea[name="message"]', rendered).val($('textarea[name="message"]', $FeedBackForm).val());

            $dialog.setContent(rendered);
          };

          p4.Mustache.Render('Feedback-SendForm', {language:language}, callback);
    }).button();

    $('.user_content .badges', this.container).disableSelection();

    $('.user_content .badges .badge .toggle', this.container).live('click', function(event){
      var $this = $(this);
      $this.toggleClass('status_off status_on');
      $this.find('input').val($this.hasClass('status_on') ? '1' : '0');

      return false;
    });

    $('.general_togglers .general_toggler', this.container).bind('click', function(){
      var feature = $(this).attr('feature');

      var $badges = $('.user_content .badge.selected', this.container);

      var toggles = $('.status_off.toggle_' + feature, $badges);

      if(toggles.length == 0)
      {
        var toggles = $('.status_on.toggle_' + feature, $badges);
      }
      if(toggles.length == 0)
      {
        humane.info('No user selected');
      }
      toggles.trigger('click');
      return false;
    });

    $('.user_content .badges .badge .deleter', this.container).live('click', function(event){
      var $elem = $(this).closest('.badge');
      $elem.fadeOut(function(){$elem.remove();});
      return;
    });

    $('.list_manager', this.container).bind('click', function(){
      $('#PushBox').hide();
      $('#ListManager').show();
      return false;
    });

    $('.options button', this.container).button();

    $('form.list_saver', this.container).bind('submit', function(){
      var $form = $(this);
      var $input = $('input[name="name"]', $form);

      var users = p4.Feedback.getUsers();

      if(users.length == 0)
      {
        humane.error('No users');
        return false;
      }

      p4.Lists.create($input.val(), function(list){$input.val('');list.addUsers(users);});

      return false;
    });

    $('input[name="users-search"]', this.container).autocomplete({
        minLength: 2,
        source: function( request, response ) {
          $.ajax({
            url: '/prod/push/search-user/',
            dataType: "json",
            data: {
              query: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function( event, ui ) {
          if(ui.item.type == 'USER')
          {
            $this.selectUser(ui.item);
          }
          if(ui.item.type == 'LIST')
          {
            for(e in ui.item.entries)
            {
              $this.selectUser(ui.item.entries[e].User);
            }
          }
          return false;
        }
      })
      .data( "autocomplete" )._renderItem = function( ul, item ) {

        var autocompleter = $('input[name="users-search"]', $this.container);

        autocompleter.addClass('loading');

        var callback = function(datas){
          $(datas).data( "item.autocomplete", item ).appendTo( ul );
          autocompleter.data( "autocomplete" ).menu.refresh();
          autocompleter.data('autocomplete')._resizeMenu();
          autocompleter.removeClass('loading');
        };

        if(item.type == 'USER')
        {
          var datas = p4.Mustache.Render('List-User-Item', item, callback);
        }
        if(item.type == 'LIST')
        {
          var datas = p4.Mustache.Render('List-List-Item', item, callback);
        }

        return;
      };

    return this;
  };

  Feedback.prototype = {
    selectUser : function(user){
      if(typeof user !== 'object')
      {
        if(window.console)
        {
          console.log('trying to select a user with wrong datas');
        }
      }
      if($('.badge_' + user.usr_id, this.container).length > 0)
      {
        humane.info('User already selected');
        return;
      }

      p4.Mustache.Render(this.Context + '-Badge', user, p4.Feedback.appendBadge);
    },
    appendBadge : function(badge){
      $('.user_content .badges', this.container).append(badge);
    },
    addUser : function($form, callback){

      var $this = this;
      $.ajax({
        type: 'POST',
        url: '/prod/push/add-user/',
        dataType: 'json',
        data: $form.serializeArray(),
        success: function(data){
          if(data.success)
          {
            humane.info(data.message);
            $this.selectUser(data.user);
            callback();
          }
          else
          {
            humane.error(data.message);
          }
        }
      });
    },
    getSelection : function() {
      return this.selection;
    },
    getUsers : function() {
      return $('.user_content .badge', this.container).map(function(){
        return $('input[name="id"]', $(this)).val();
      });
    }
  };



  var ListManager = function($container) {

    this.list = null;
    this.container = $container;

    $('.back_link', this.container).bind('click', function(){
      $('#PushBox').show();
      $('#ListManager').hide();
      return false;
    }).button();

    $('a.list_sharer', this.container).die('click').live('click', function(){

      var $this = $(this),
      options = {
        size : 'Small',
        closeButton : true,
        title : $this.attr('title')
      },
      $dialog = p4.Dialog.Create(options, 2);

      $dialog.load($this.attr('href'), 'GET')

      return false;
    });


    $('a.user_adder', this.container).bind('click', function(){

      var $this = $(this);

      $.ajax({
        type: "GET",
        url: $this.attr('href'),
        dataType: 'html',
        beforeSend:function(){
          if($('#user_adder_dialog').length == 0)
          {
            $('body').append('<div id="user_adder_dialog" style="display:none;"></div>');
          }
          $('#user_adder_dialog').addClass('loading').empty().dialog({
              buttons:{},
              draggable:false,
              resizable:false,
              closeOnEscape:true,
              modal:true,
              width:'400',
              height:'400'
          }).dialog( "moveToTop" );
        },
        success: function(data){
          $('#user_adder_dialog').removeClass('loading').empty().append(data);
          return;
        },
        error: function(){
          $('#user_adder_dialog').dialog('destroy');
          $('#user_adder_dialog').remove();
          return;
        },
        timeout: function(){
          $('#user_adder_dialog').dialog('destroy');
          $('#user_adder_dialog').remove();
          return;
        }
      });

      return false;
    });



    var initLeft = function() {
      $('a.list_refresh', $container).bind('click', function(event){

        var callback = function(datas){
          $('.all-lists', $container).removeClass('loading').append(datas);
          initLeft();
        };

        $('.all-lists', $container).empty().addClass('loading');

        p4.Lists.get(callback, 'html');

        return false;
      });

      $('a.list_adder', $container).bind('click', function(event){

        var makeDialog = function (box) {

          var buttons = {};

          buttons[language.valider] = function() {

            var callbackOK = function () {
              $('a.list_refresh', $container).trigger('click');
              p4.Dialog.get(2).Close();
            };

            var name = $('input[name="name"]', p4.Dialog.get(2).getDomElement()).val();

            if($.trim(name) === '')
            {
              alert(language.listNameCannotBeEmpty);
              return;
            }

            p4.Lists.create(name, callbackOK);
          };

          var options = {
            cancelButton : true,
            buttons : buttons,
            size:'Alert'
          };

          p4.Dialog.Create(options, 2).setContent(box);
        };

        p4.Mustache.Render('ListEditor-DialogAdd', language, makeDialog);

        return false;
      });

      $('li.list a.list_link', $container).bind('click', function(event){

        var $this = $(this);

        $this.closest('.lists').find('.list.selected').removeClass('selected');
        $this.parent('li.list').addClass('selected');

        $.ajax({
          type: 'GET',
          url: $this.attr('href'),
          dataType: 'html',
          success: function(data){
            $('.editor', $container).removeClass('loading').append(data);
            initRight();
          },
          beforeSend: function(){
            $('.editor', $container).empty().addClass('loading');
          }
        });

        return false;
      });
    };

    var initRight = function(){

      var $container = this.container;

      $('form[name="list-editor-search"]', this.container).bind('submit', function(){

        var $this = $(this);
        var dest = $('.list-editor-results', $container);

        $.ajax({
          url: $this.attr('action'),
          type: $this.attr('method'),
          dataType: "html",
          data: $this.serializeArray(),
          beforeSend : function () {
            dest.empty().addClass('loading');
          },
          success: function( datas ) {

            dest.empty().removeClass('loading').append(datas);
          }
        });
        return false;
      });

      $('form[name="list-editor-search"] select', this.container).bind('change', function(){
        $(this).closest('form').trigger('submit');
      });

      $('button', this.container).button();

      $('.EditToggle', $('#ListManager')).bind('click', function(){
        $('.content.readonly, .content.readwrite', $('#ListManager')).toggle();
        return false;
      });

      $('button.deleter', this.container).bind('click', function(event){

        var list_id = $(this).find('input[name=list_id]').val();

        var makeDialog = function (box) {

          var buttons = {};

          buttons[language.valider] = function() {

            var callbackOK = function () {
              $('a.list_refresh', $container).trigger('click');
              p4.Dialog.get(2).Close();
            };

            var List = new document.List(list_id);
            List.remove(callbackOK);
          };

          var options = {
            cancelButton : true,
            buttons : buttons,
            size:'Alert'
          };

          p4.Dialog.Create(options, 2).setContent(box);
        };

        p4.Mustache.Render('ListEditor-DialogDelete', language, makeDialog);

        return false;
      });

//
//      $('.editor input[name="list-add-user"]', this.container).autocomplete({
//          minLength: 2,
//          source: function( request, response ) {
//            $.ajax({
//              url: '/prod/push/search-user/',
//              dataType: "json",
//              data: {
//                query: request.term
//              },
//              success: function( data ) {
//                response( data );
//              }
//            });
//          },
//          select: function( event, ui ) {
//            if(ui.item.type == 'USER')
//            {
//              var callback = function(list, datas) {
//                if($.inArray(ui.item.usr_id, datas.result) >= 0)
//                {
//                  p4.Mustache.Render('List-Badge', ui.item, p4.ListManager.appendBadge);
//                }
//                $('.counter.current, .list.selected .counter', $('#ListManager')).each(function(){
//                  $(this).text(parseInt($(this).text()) + datas.result.length);
//                });
//                console.log('increment counter');
//              }
//              p4.ListManager.getList().addUser(ui.item.usr_id, callback);
//            }
//            return false;
//          }
//        })
//        .data( "autocomplete" )._renderItem = function( ul, item ) {
//
//          var autocompleter = $('.editor input[name="list-add-user"]', this.container);
//
//          autocompleter.addClass('loading');
//
//          var callback = function(datas){
//            $(datas).data( "item.autocomplete", item ).appendTo( ul );
//            autocompleter.data( "autocomplete" ).menu.refresh();
//            autocompleter.data('autocomplete')._resizeMenu();
//            autocompleter.removeClass('loading');
//          };
//
//          if(item.type == 'USER')
//          {
//            var datas = p4.Mustache.Render('List-User-Item', item, callback);
//          }
//
//          return;
//        };
    };


    initLeft();

    $('.badges a.deleter', this.container).live('click', function(){

      var badge = $(this).closest('.badge');

      var usr_id = badge.find('input[name="id"]').val();


      var callback =  function(list, datas){
        $('.counter.current, .list.selected .counter', $('#ListManager')).each(function(){
          $(this).text(parseInt($(this).text()) - 1);
        });

        badge.remove();
      };

      p4.ListManager.getList().removeUser(usr_id, callback);

      return false;
    });

  };

  ListManager.prototype = {
    workOn : function(list_id) {
      this.list = new document.List(list_id);
    },
    getList : function(){
      return this.list;
    },
    appendBadge : function(datas) {
      $('#ListManager .badges').append(datas);
    }
  };



  window.Feedback = Feedback;
  window.ListManager = ListManager;

}(window));
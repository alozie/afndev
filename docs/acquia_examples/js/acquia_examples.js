(function ($) {
  Drupal.behaviors.acquiaExamples = {
    attach: function (context, settings) {
      // Get our setting from Drupal.settings
      var mySetting = settings.acquiaExamples;

      console.log('Setting: ' + mySetting);

      // This will only ever get attached one time.
      $('#a-thing').once('acquiaExamples', function () {
        var myFunctionThatModifiesTheDOM = function () {
          // Do a thing.
          $('#modified-thing').html('hello world');

          // Tell Drupal that you changed this thing so it can run functions on it.
          Drupal.attachBehaviors();
        }

        $('#a-button').click(function(){
          $x = 10;
          myFunctionThatModifiesTheDOM();
        });

      });

      // This will execute every time the DOM is changed.
      $('blockquote').each(function(i, element){
        console.log(element);
      });

      var myFunction = function () {
        // Do a thing.
        $x = 0;

        return 'hello world';
      }
    }
  };
})(jQuery);

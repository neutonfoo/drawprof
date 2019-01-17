$(document).ready(function() {
  // Initialize Canvas
  var desktopWidth = 500
  var desktopHeight = 700

  var mobileWidth = 300
  var mobileHeight = 400

  var canvas = document.getElementById('c');
  var ctx = canvas.getContext('2d');

  var isMobile = false;

  // If Mobile
  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
      || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {

    canvas.width = mobileWidth
    canvas.height = mobileHeight

    isMobile = true
  } else {
    // alert(desktopWidth + ' ' + desktopHeight)

    canvas.width = desktopWidth
    canvas.height = desktopHeight
  }


  // Set up mouse events for drawing
  var drawing = false;
  var mousePos = { x:0, y:0 };
  var lastPos = mousePos;
  canvas.addEventListener("mousedown", function (e) {
    drawing = true;
    lastPos = getMousePos(canvas, e);
    ctx.beginPath();
    ctx.moveTo(lastPos.x, lastPos.y);
  }, false);
  canvas.addEventListener("mouseup", function (e) {
    drawing = false;
    ctx.closePath();
  }, false);
  canvas.addEventListener("mousemove", function (e) {
    mousePos = getMousePos(canvas, e);
    if(drawing == true) {
      ctx.lineTo(mousePos.x, mousePos.y);
      ctx.stroke();
    }
  }, false);

  // Get the position of the mouse relative to the canvas
  function getMousePos(canvasDom, mouseEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: mouseEvent.clientX - rect.left,
      y: mouseEvent.clientY - rect.top
    };
  }

  // Set up touch events for mobile, etc
  canvas.addEventListener("touchstart", function (e) {
    e.preventDefault();

    mousePos = getTouchPos(canvas, e);
    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousedown", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
  }, false);
  canvas.addEventListener("touchend", function (e) {
    e.preventDefault();

    var mouseEvent = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(mouseEvent);
  }, false);
  canvas.addEventListener("touchmove", function (e) {
    e.preventDefault();

    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousemove", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
  }, false);

  // Get the position of a touch relative to the canvas
  function getTouchPos(canvasDom, touchEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: touchEvent.touches[0].clientX - rect.left,
      y: touchEvent.touches[0].clientY - rect.top
    };
  }

  // Initialize vars
  ctx.strokeStyle = '#000000';
  ctx.lineWidth = 5;

  // Size Button
  $('.canvasStrokeSizeRadio').on('click', function() {
    var strokeSize = $(this).val()
    ctx.lineWidth = strokeSize
  });

  // Color Picker
  var $colorPickerContainer = $('#colorPickerContainer')
  var colors = ['#FFFFFF', '#E4E4E4', '#888888', '#222222', '#FDA8D1', '#E20A17', '#E39423', '#9F6A45', '#E4D72F', '#96DE50', '#1DBC20', '#23D3DC', '#1484C5', '#0920E6', '#CE72E2', '#810F7E']
  $.each(colors, function(colorIndex, color) {
    $colorPickerContainer.append(`<span class="color" style="background-color:${ color }" data-colorindex="${ colorIndex }"></span>`)
  })

  $('.color').on('click', function() {
    ctx.strokeStyle = colors[$(this).data('colorindex')];
  })

  // Clear Canvas Button
  var $clearCanvasButton = $('#clearCanvasButton')
  $clearCanvasButton.on('click', function() {
    if(confirm('Are you sure you want to clear the canvas?')) {
      ctx.clearRect(0, 0, canvas.width, canvas.height);      
    }
  })

  // Other
  var profUrl = ''
  var profName = ''
  var uniName = ''

  var $profName = $('.profName')
  var $uniName = $('.uniName')

  var $loadProfUrl = $('#loadProfUrl')
  var $loadProfButton = $('#loadProfButton')

  // $loadProfButton
  $loadProfButton.on('click', function() {
    profUrl = $loadProfUrl.val()

    if(profUrl.substring(0, 48) != 'http://ratemyprofessors.com/ShowRatings.jsp?tid='
    && profUrl.substring(0, 49) != 'https://ratemyprofessors.com/ShowRatings.jsp?tid='
    && profUrl.substring(0, 52) != 'http://www.ratemyprofessors.com/ShowRatings.jsp?tid='
    && profUrl.substring(0, 53) != 'https://www.ratemyprofessors.com/ShowRatings.jsp?tid='
  ) {
      profUrl = ''
      alert('The url must start with:\n"http://www.ratemyprofessors.com/ShowRatings.jsp?tid=" or\n"https://www.ratemyprofessors.com/ShowRatings.jsp?tid=".')
    } else {
        $.ajax({
          method: 'POST',
          url: 'profGetter.php',
          data: {
            profUrl: profUrl
          }
        }).done(function(response) {

          // 404 or Invalid link
          if(response == 'Rate My Professors -  Review Teachers and Professors, School Reviews, College Campus Ratings') {
            alert('Not a valid URL.')
          } else {
            var titleSections = response.split('|')

            profName = titleSections[0]
            uniName = titleSections[1]

            $profName.html(profName)
            $uniName.html(uniName)
          }
        });
    }
  })

  // Upload button
  var $submitForm = $('#submitForm')
  var $submitButton = $('#submitButton')
  var $artistName = $('#artistName')

  var $profUrlInput = $('#profUrlInput')
  var $artistNameInput = $('#artistNameInput')
  var $imageDataUrlInput = $('#imageDataUrlInput')
  var $isMobileInput = $('#isMobileInput')

  $submitButton.on('click', function() {
    if(profUrl === '' || profName === '' || uniName === '') {
      alert('You have to load a professor before submitting.')
    } else {
      $profUrlInput.val(profUrl)
      $artistNameInput.val($artistName.val())

      if(isMobile == true) {
        $isMobileInput.val(1)
      } else if(isMobile == false) {
        $isMobileInput.val(0)
      }

      var imageDataURL = canvas.toDataURL();
      $imageDataUrlInput.val(imageDataURL)

      $submitForm.submit()
    }
  })
})

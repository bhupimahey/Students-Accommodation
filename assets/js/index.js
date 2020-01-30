   $(function() {
      $('#slides').slidesjs({
        width: 500,
        height: 437,
        navigation: {
          effect: "fade"
        },
        pagination: {
          effect: "fade"
        },
        effect: {
          fade: {
            speed: 1800
          }
        },
        play: {
          effect: "fade",
          auto: true
        }
      });
    });
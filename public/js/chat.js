String.prototype.format = function () {
  var formatted = this;
  for (var i = 0; i < arguments.length; i++) {
    var regexp = new RegExp('\\{' + i + '\\}', 'gi');
    formatted = formatted.replace(regexp, arguments[i]);
  }
  return formatted;
};
const firebaseConfig = {
  apiKey: "AIzaSyD2JpzCkFjO-9QgGVKQCkWEWe34dBy1qbY",
  authDomain: "pengaduanapp-87882.firebaseapp.com",
  projectId: "pengaduanapp-87882",
  storageBucket: "pengaduanapp-87882.appspot.com",
  messagingSenderId: "206245474019",
  appId: "1:206245474019:web:0eab579a0fe91977e25fd6",
  measurementId: "G-QNNDLDDHXN"
};

if ($("#notif .badge").length > 0 && $("meta[name=role]").attr('content') != '0') {
  firebase.initializeApp(firebaseConfig);

  const messaging = firebase.messaging();
  requestPermission();
  var x = document.getElementById("notif-tone");
  var notif = `
  <a href="{0}" class="dropdown-item">
    <div class="media">
      <div class="media-body">
        <h3 class="dropdown-item-title font-weight-bold">
          {1}
        </h3>
        <p class="text-sm">{2}</p>
        <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {3}</p>
      </div>
    </div>
  </a>
  `;
  var cw = `<div class="card card-danger card-outline">
    <div class="card-body p-0">
      <div class="mailbox-read-info">
        <h6>
          <span class="font-weight-bold text-uppercase">
           {0}
          </span>
          <span class="mailbox-read-time float-right">{1}</span>
        </h6>
      </div>
      <div class="mailbox-read-message">
        {2}
      </div>
    </div>
  </div>`;
  var ncount = Number($("#notif-count").text());
  messaging.onMessage((payload) => {
    if ($("#message-wrap").length > 0) {
      $.get('/readed', { notif: payload.data.notif }, function (res) { }, 'json');
      var n = cw.format(payload.data.title, payload.data.time, payload.data.message_full);
      $("#message-wrap").append(n);
      // $('html,body').animate({
      //   scrollTop: $(".card-danger").last().offset().top - 100
      // });
    } else {
      ncount++;
      $("title").html("(" + ncount + ") " + $("title").text());
      $("#notif-count").text(ncount);
      if (!$("#notif-count").is(":visible")) {
        $("#notif-count").removeClass("d-none");
        $("#notif-none").remove();
      }
      var n = notif.format(payload.data.url, payload.data.title, payload.data.message, payload.data.time);
      $("#notif-wrapper").prepend(n);
    }

    x.play();
  });
  function requestPermission() {
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        getToken();
      } else {
        toastr.error('Notifikasi tidak berfungsi. Pastikan anda menerima notifikasi dari website ini!');
      }
    });
  }

  function getToken() {
    messaging.getToken({ vapidKey: 'BJP0xliVPKv1T9T1N4E4jGox6zIIcn_gcdNryL_Na7B8U_etQS2e6w5WP2hQKT2AUsqt1FYO7IULrWPCPvjVIX0' }).then((_token) => {
      if (_token) {
        sendTokentoServer(_token);
      }
    })
  }

  function sendTokentoServer(token) {
    const data = {
      _token: $("meta[name=_csrf]").attr('content'),
      fb_token: token,
      _method: 'PUT'
    };
    $.ajax({
      url: '/token',
      type: 'PUT',
      dataType: 'json',
      data: data,
      success: function (res) { },
      error: function (err) {
        console.log(err);
      }
    });
  }
}
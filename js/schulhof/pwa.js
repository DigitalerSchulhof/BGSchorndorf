function urlB64ToUint8Array(base64String) {
  const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, "+")
    .replace(/_/g, "/");

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

$(document).ready((_) => {
  if ("serviceWorker" in navigator) {
    if ("PushManager" in window) {
      navigator.serviceWorker
        .register("sw.js")
        .then(function (swReg) {
          console.log("Service Worker registriert.");

          swRegistration = swReg;

          swRegistration.pushManager
            .getSubscription()
            .then(function (subscription) {
              sub = subscription !== null;

              if (!sub) {
                $("#cms_push_verboten").show();
                $("#cms_push_verboten button").attr("disabled", false);
                $("#cms_push_verboten button").click((_) => {
                  const applicationServerKey = urlB64ToUint8Array(
                    "BBK52oA1tdPtligx1uQugdyA9huXmT2e0Dn5a2oxU-N9V33XoEu3P827nNXWuDVAQFtXIfuMD6AxhKPlvTWRvU8"
                  );
                  swRegistration.pushManager
                    .subscribe({
                      userVisibleOnly: true,
                      applicationServerKey: applicationServerKey,
                    })
                    .then(function (subscription) {
                      console.log("Push akzeptiert");
                      cms_ajaxanfrage(
                        384,
                        "Push-Benachrichtigungen werden akzeptiert",
                        { sub: JSON.stringify(subscription) }
                      ).then(_ => cms_laden_aus());
                      $("#cms_push_verboten").hide();
                    })
                    .catch(function (err) {
                      console.log("Push akzeptieren fehlgeschlagen: ", err);
                      cms_meldung_an(
                        "fehler",
                        "Fehlgeschlagen",
                        "Eventuell sind Push-Benachrichtigungen im Browser standardmäßig deaktiviert, und müssen manuell aktiviert werden.",
                        '<button class="cms_button" onclick="cms_meldung_aus()">OK</button>'
                      );
                    });
                });
              }
            });
        })
        .catch(function () {
          console.log("Service Worker nicht registriert.");
        });
    } else {
      $("#cms_push_nicht_unterstuetzt").show();
      navigator.serviceWorker
        .register("sw.js")
        .then(function () {
          console.log("Service Worker registriert.");
        })
        .catch(function () {
          console.log("Service Worker nicht registriert.");
        });
    }
  }
});
a2hs = {
  prompt: null,
  handler: (e) => {
    a2hs.prompt = e;
    let box = $("#dshPWAInstallation");
    box.show();
  },
  install: (_) => {
    let box = $("#dshPWAInstallation");
    a2hs.prompt.prompt();
    a2hs.prompt.userChoice.then((r) => {
      if (r.outcome === "accepted") {
        console.log("A2HS akzeptiert");
        box.hide();
      } else {
        console.log("A2HS doch nicht");
        box.show();
      }
      a2hs.prompt = undefined;
    });
  },
};
window.addEventListener("beforeinstallprompt", (e) => {
  a2hs.handler(e);
});

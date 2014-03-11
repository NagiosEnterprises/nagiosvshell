var NagiosCollection = function (baseURL,type) {

  this.BASE_URL = baseURL;
  this.TYPE = type;
  this.collection = [];
};

NotificationsService.prototype.push = function (notification) {

  var notificationToArchive;
  var newLen = this.notifications.unshift(notification);
  if (newLen > this.MAX_LEN) {
    notificationToArchive = this.notifications.pop();
    this.notificationsArchive.archive(notificationToArchive);
  }
};

NotificationsService.prototype.getCurrent = function () {
  return this.notifications;
};

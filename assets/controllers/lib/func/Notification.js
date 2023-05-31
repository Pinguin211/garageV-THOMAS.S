import $ from "jquery";

export function createNotification(message, notification_container_id = '#notificationContainer') {
    const container = $(notification_container_id)
    const notification = $('<div>').addClass('notification d-inline-flex align-items-center justify-content-between')
    const messageElement = $('<div>').addClass('m-2').text(message)
    const closeElement = $('<span>').addClass('notification-close').text('×')
    closeElement.click(function() {
        notification.remove()
    })
    notification.append(messageElement)
    notification.append(closeElement)
    container.append(notification)

    setTimeout(function() {
        notification.fadeOut(1500, function() {
            notification.remove();
        });
    }, 3000);
}

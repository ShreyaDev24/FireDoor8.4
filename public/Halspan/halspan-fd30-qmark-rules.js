/**
 * Halspan FD30 + Q-Mark rules.
 * Applies only when #qMarkEnabled=1 AND pageIdentity=2 AND fireRating=FD30.
 * When Q-Mark is off (or core/rating differ) existing behaviour is left unchanged.
 */
function isQMarkEnabled() {
    return String($('#isQmarkORCertifireEnabled').val()) === '1';
}




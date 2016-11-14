function deleteAccount() {
	OC.dialogs.confirm('Do you really want to delete your account ? All your data will be lost !', 'Delete your account ?', function (res) {
		if (res) {
			$.ajax({
				type: 'POST',
				contentType: "application/json",
				url: OC.generateUrl('/apps/userdeletion/deleteaccount'),
				data: JSON.stringify({
					id: OC.getCurrentUser().uid
				}),
				success: function (data) {
					if (data.status === 'success') {
						OC.dialogs.info('Your account has been deleted. Click OK to be redirected to login page.', 'Account deleted', function() {
							OC.redirect('');
						}, true);
					}
				}
			});
		}
	}, true);
}

$(document).ready(function () {
	$("#deleteAccount-btn").click(deleteAccount);
});


var app = angular.module('reservations', []);

app.controller('MainController', ['$scope', function($scope) {
	$scope.addReservation = function addReservation(onDate) {
		$scope.reservation = {
			closed: false,
			location: $scope.selectedLocation.id,
			activity: '',
			date: onDate,
			duration: 60,
			createdBy: null,
			id: null
		};
		$('#editEventModal').modal('show');
	};
	$scope.editReservation = function editReservation(event) {
		$scope.selectedReservation = event;
		$('#editEventModal').modal('show');
	};
	$scope.getReservationTitle = function getReservationTitle(reservation) {
		return reservation.activity;
	};

	// REST hooks
	$scope.loadLocations = function loadLocations() {
		$scope.locations = [
			{ id: 123123, title: 'Mehrzweckplatz', reservations: [] },
			{ id: 234234, title: 'Bandraum', reservations: [] }
		];
		$scope.selectedLocation = $scope.locations[0];
	};
	$scope.createReservation = function createReservation(reservation) {
		if (reservation.id) {
			// edit
			var reservations = $scope.selectedLocation.reservations;
			for (var i = 0; i < reservations.length; i++) {
				if (reservations[i].id == reservation.id)
					return reservations[i] = event;
			}
		} else {
			// create
			$scope.selectedLocation.reservations.push(reservation);
		}
	};
	$scope.deleteReservation = function deleteReservation(reservation) {
		var reservations = $scope.selectedLocation.reservations;
		for (var i = 0; i < reservations.length; i++) {
			if (reservations[i].id == reservation.id)
				return reservations.splice(i, 1);
		}
	};

	$scope.loadLocations();
}]);


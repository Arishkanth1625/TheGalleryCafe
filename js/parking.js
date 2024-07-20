document.addEventListener('DOMContentLoaded', function() {
    const floor1 = document.getElementById('floor1');
    const floor2 = document.getElementById('floor2');
    const numSpotsPerFloor = 20;

    for (let i = 1; i <= numSpotsPerFloor; i++) {
        const spot1 = document.createElement('div');
        spot1.classList.add('spot');
        spot1.setAttribute('data-floor', '1');
        spot1.setAttribute('data-spot', i);
        spot1.textContent = `Spot ${i}`;
        spot1.onclick = () => selectSpot(spot1);
        floor1.appendChild(spot1);

        const spot2 = document.createElement('div');
        spot2.classList.add('spot');
        spot2.setAttribute('data-floor', '2');
        spot2.setAttribute('data-spot', i);
        spot2.textContent = `Spot ${i}`;
        spot2.onclick = () => selectSpot(spot2);
        floor2.appendChild(spot2);
    }
});

let selectedSpot = null;

function selectSpot(element) {
    if (selectedSpot) {
        selectedSpot.classList.remove('selected');
    }
    selectedSpot = element;
    selectedSpot.classList.add('selected');
    document.getElementById('floorNumber').value = selectedSpot.getAttribute('data-floor');
    document.getElementById('spotNumber').value = selectedSpot.getAttribute('data-spot');
}

document.getElementById('bookingForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const floorNumber = document.getElementById('floorNumber').value;
    const spotNumber = document.getElementById('spotNumber').value;
    const userName = document.getElementById('userName').value;
    
    if (floorNumber && spotNumber && userName) {
        document.getElementById('confirmationMessage').innerText = `Floor ${floorNumber} Spot ${spotNumber} booked successfully for ${userName}.`;
        document.getElementById('confirmation').style.display = 'block';

        const spotElement = document.querySelector(`.spot[data-floor="${floorNumber}"][data-spot="${spotNumber}"]`);
        spotElement.classList.add('booked');
        spotElement.classList.remove('selected');

        selectedSpot = null;
        document.getElementById('floorNumber').value = '';
        document.getElementById('spotNumber').value = '';
        document.getElementById('userName').value = '';
    } else {
        alert('Please select a parking spot and enter your name.');
    }
});

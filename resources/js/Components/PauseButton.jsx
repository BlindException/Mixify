import React from 'react';


import axios from 'axios';


const PauseButton= ({ handleTrackChange }) => {


const handleClick = () => {


axios.post('/playback/pause')


.then(response => {


handleTrackChange(response.data, false); // set isPlaying to false


})


.catch(error => {


console.error(error);


});


}


return (


<button onClick={handleClick}>Pause</button>


);


}


export default PauseButton;
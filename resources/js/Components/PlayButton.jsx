import React from 'react';


import axios from 'axios';


const PlayButton = ({ handleTrackChange }) => {


const handleClick = () => {


axios.post('/playback/play')


.then(response => {


handleTrackChange(response.data, true); // set isPlaying to true


})


.catch(error => {


console.error(error);


});


}


return (

<>
<button onClick={handleClick}>Play</button>
</>

);


}


export default PlayButton;
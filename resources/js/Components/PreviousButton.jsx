import React, { useState } from 'react';


import axios from 'axios';


const PreviousButton = ({ handleTrackChange }) => {





const handleClick = () => {


axios.post('/playback/previous')


.then(response => {

    Object.keys(response.data).forEach(key => {


        console.log(key, response.data[key]);
        
        
        });

        handleTrackChange(response.data, true); // set isPlaying to true


})


.catch(error => {


console.error(error);


});


}


return (


<button onClick={handleClick}>Previous</button>


);


}


export default PreviousButton;
import React, { useState } from 'react';


import axios from 'axios';


const DeleteButton = ({}) => {





const handleClick = () => {


    axios.delete('/playback/destroy')
.then(response => {
    if (response.status === 200) {
        // Redirect to dashboard
        window.location.href='dashboard';
                        } else {
                        // Handle error
                        console.error(response.data.message);
                
        }        
    
})


.catch(error => {


console.error(error);


});


}


return (


<button onClick={handleClick}>Delete</button>
);

}


export default DeleteButton;
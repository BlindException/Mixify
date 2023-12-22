import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm, Head } from '@inertiajs/react';
import { useState } from 'react';
import PlayButton from '@/Components/PlayButton';
import PauseButton from '@/Components/PauseButton';
import NextButton from '@/Components/NextButton';
import PreviousButton from '@/Components/PreviousButton';
import DeleteButton from '@/Components/DeleteButton';

export default function Index(props) {
    const [currentTrack, setCurrentTrack] = useState(null);
    const [isPlaying, setIsPlaying] = useState(true);
    const handleTrackChange = (newTrack, playing) => {
                setCurrentTrack(newTrack);
                setIsPlaying(playing);
                               };
return (
                        <AuthenticatedLayout
                        auth={props.auth}
           
        errors={props.errors}
        
        
        header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Mixify Playback</h2>}
        
        
        >
        
        
        <Head title="Mixify Playback" />
        
        
        <div className="py-12">
        
        
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        
        
        <div className="p-6 text-gray-900">
        
        
        <h1 className="text-gray-700 mb-4 border-b-2 border-gray-300 pb-4">
        {currentTrack ? currentTrack.name : props.name}
                                </h1>

        
        
        


                                {currentTrack ? (


currentTrack.artists.map((artist) => (


<h2 key={artist}>{artist + ' '}</h2>


))


) : (


props.artists.map((artist) => (


<h2 key={artist}>{artist + ' '}</h2>


))


)}
<PreviousButton handleTrackChange={handleTrackChange} isPlaying={true} />
{isPlaying ? (


<PauseButton handleTrackChange={handleTrackChange} isPlaying={false} />


) : (

<PlayButton handleTrackChange={handleTrackChange} isPlaying={true} />

)}
<NextButton handleTrackChange={handleTrackChange} isPlaying={true} />
              <DeleteButton/>
                                                                             </div>
                        </div>
                        </div>
                        </div>
                        </AuthenticatedLayout>
                        );
                        }
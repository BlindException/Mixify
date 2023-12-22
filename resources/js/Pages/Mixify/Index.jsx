import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useForm, Head } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Index(props) {
    
        
        const { data, setData, post, processing, reset, errors } = useForm({
            selectedLists: '',
            selectedDevice:'',
        });
        const handleDeviceChange = (e) => {
            const selectedDeviceId = e.target.value;
                                    setData({ ...data, selectedDevice: selectedDeviceId }); // Update the selected device value
                                    };
        const submit = (e) => {
            e.preventDefault();
            post(route('mixify.store'), { onSuccess: () => reset() });
        };
        return (
                        <AuthenticatedLayout
                        auth={props.auth}
        
        
        errors={props.errors}
        
        
        header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Create Mixify List</h2>}
        
        
        >
        
        
        <Head title="Create Mixify List" />
        
        
        <div className="py-12">
        
        
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        
        
        <div className="p-6 text-gray-900">
        
        
        <p className="text-gray-700 mb-4 border-b-2 border-gray-300 pb-4">
        
        
        To create a Mixify list, select 2 or more of your playlists and click the 'Create Mixify List' button. You will be redirected to a page to play your new Mixify List.
        
        
        </p>
        
        
        <form onSubmit={submit}>
        <select value={data.selectedDevice} onChange={handleDeviceChange}>
<option value="">Select Device For Playback</option> // Add the option with the desired message and empty value
{props.devices.map((device) => (
<option key={device.id} value={device.id}>
{device.name}
</option>
))}
</select>        
        <ul>
        
        
        {props.playlists.map((playlist) => (
        
        
        <li key={playlist.id}>
                        <input type="checkbox" id={playlist.id} value={playlist.id} name={data.selectedLists} 
        
                        onChange={(e) => {


const selectedPlaylistId = e.target.value;


const updatedSelectedLists = data.selectedLists.includes(selectedPlaylistId)


? data.selectedLists.filter((id) => id !== selectedPlaylistId)


: [...data.selectedLists, selectedPlaylistId];


setData({ ...data, selectedLists: updatedSelectedLists });


}}


/>
        <label htmlFor={playlist.id}>{playlist.name}</label>
        
        
        </li>
        
        
        ))}
        
        
        </ul>
        
        
        <PrimaryButton type="submit">Create Mixify List</PrimaryButton>
        
        
        </form>
        
        
        </div>
        
        
        </div>
        
        
        </div>
        
        
        </div>
        
        
        </AuthenticatedLayout>
        
        
        );
        
        
        }
import { useState, useEffect } from 'react';
import api from '../../lib/axios';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export default function WebDev() {
    const navigate = useNavigate();
    const { user } = useAuth();
    const [formData, setFormData] = useState({
        tim_id: '',
        email_ketua: '',
        judul_proyek: '',
        link_github: '',
        link_demo: '',
        link_hosting: '',
    });
    const [teams, setTeams] = useState([]);
    const [pdfFile, setPdfFile] = useState(null);
    const [pptFile, setPptFile] = useState(null);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [message, setMessage] = useState('');

    useEffect(() => {
        const fetchTeams = async () => {
            try {
                const response = await api.get('/tim');
                // Filter teams to Web Development or just show all for now
                // Assuming backend returns an array of teams inside response.data.data
                const webDevTeams = response.data.data.filter(t => t.cabang_lomba?.nama === 'WEB DEVELOPMENT');
                setTeams(webDevTeams);
            } catch (error) {
                console.error("Failed to fetch teams", error);
            }
        };
        
        if (user && user.role !== 'admin' && user.role !== 'peserta_webdev') {
            alert('Anda hanya bisa mengakses halaman Web Development');
            navigate('/dashboard');
        } else {
            fetchTeams();
        }
    }, [user, navigate]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);
        setMessage('');

        const data = new FormData();
        Object.keys(formData).forEach(key => {
            data.append(key, formData[key]);
        });
        if (pdfFile) data.append('pdf', pdfFile);
        if (pptFile) data.append('ppt', pptFile);

        try {
            await api.post('/webdev-progress', data, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            setMessage('Project submitted successfully!');
            setTimeout(() => navigate('/dashboard'), 2000);
        } catch (error) {
            if (error.response?.data?.errors) {
                const errorMessages = Object.values(error.response.data.errors).flat().join(', ');
                setMessage(errorMessages);
            } else {
                setMessage(error.response?.data?.message || 'Submission failed.');
            }
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    return (
        <div className="max-w-3xl mx-auto py-8">
            <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-bl-[100px] -z-0"></div>
                <div className="absolute bottom-0 left-0 w-40 h-40 bg-purple-50 rounded-tr-[80px] -z-0"></div>

                <div className="relative z-10 p-8 sm:p-12">
                    <button onClick={() => navigate('/dashboard')} className="mb-6 flex items-center text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                        <svg className="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" /></svg>
                        Kembali ke Dashboard
                    </button>
                    <div className="mb-10 flex items-start justify-between">
                        <div>
                            <div className="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                                <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            </div>
                            <h1 className="text-3xl font-black text-slate-900 mb-2">Web Development</h1>
                            <p className="text-slate-500">Submit your final project details and documentation.</p>
                        </div>
                    </div>

                    {message && (
                        <div className={`mb-8 p-4 rounded-xl text-sm font-medium flex items-center ${message.includes('success') ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'}`}>
                            <svg className="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {message}
                        </div>
                    )}

                    <form onSubmit={handleSubmit} className="space-y-8">
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-2">Select Your Team</label>
                            <select 
                                name="tim_id" 
                                required 
                                value={formData.tim_id}
                                onChange={handleChange}
                                className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            >
                                <option value="" disabled>-- Choose Team --</option>
                                {teams.map(team => (
                                    <option key={team.id} value={team.id}>
                                        {team.nama} {team.cabang_lomba?.nama ? `(${team.cabang_lomba.nama})` : ''}
                                    </option>
                                ))}
                            </select>
                            {teams.length === 0 && <p className="text-xs text-red-500 mt-2">No teams found. Please contact admin to register your team first.</p>}
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">Team Leader Email</label>
                                <input type="email" name="email_ketua" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" onChange={handleChange} />
                            </div>
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">Project Title</label>
                                <input type="text" name="judul_proyek" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" onChange={handleChange} />
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">GitHub Repository URL</label>
                                <input type="url" name="link_github" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="https://github.com/..." onChange={handleChange} />
                            </div>
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">Video Demo URL (YouTube/Drive)</label>
                                <input type="url" name="link_demo" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="https://youtube.com/..." onChange={handleChange} />
                            </div>
                        </div>

                        <div className="mt-8 mb-8">
                            <label className="block text-sm font-bold text-slate-700 mb-2">Website Hosting URL (Optional)</label>
                            <input type="url" name="link_hosting" className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="https://..." onChange={handleChange} />
                        </div>

                        <div className="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-6">
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">Upload Documentation (PDF)</label>
                                <input type="file" accept=".pdf" required className="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 file:cursor-pointer file:transition-colors cursor-pointer" onChange={(e) => setPdfFile(e.target.files[0])} />
                            </div>
                            <div>
                                <label className="block text-sm font-bold text-slate-700 mb-2">Upload Pitch Deck (PPT/PDF)</label>
                                <input type="file" accept=".ppt,.pptx,.pdf" required className="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 file:cursor-pointer file:transition-colors cursor-pointer" onChange={(e) => setPptFile(e.target.files[0])} />
                            </div>
                        </div>

                        <div className="flex items-center justify-between pt-6 border-t border-slate-100">
                            <button type="button" onClick={() => navigate('/dashboard')} className="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" disabled={isSubmitting} className="px-8 py-3.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-300 shadow-lg shadow-indigo-200">
                                {isSubmitting ? 'Submitting...' : 'Submit Project'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}

import { useState, useEffect } from 'react';
import { QRCodeSVG } from 'qrcode.react';
import api from '../../lib/axios';
import { useNavigate, Link } from 'react-router-dom';

export default function SeminarRegister() {
    const navigate = useNavigate();
    const [formData, setFormData] = useState({
        nama: '',
        instansi: '',
        email: '',
        no_hp: '',
    });
    const [buktiFile, setBuktiFile] = useState(null);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [message, setMessage] = useState('');
    const [ticketData, setTicketData] = useState(null);

    useEffect(() => {
        const savedTicket = localStorage.getItem('innoventure_seminar_ticket');
        if (savedTicket) {
            setTicketData(JSON.parse(savedTicket));
        }
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);
        setMessage('');

        const data = new FormData();
        Object.keys(formData).forEach(key => {
            data.append(key, formData[key]);
        });
        if (buktiFile) data.append('bukti_follow_ig', buktiFile);

        try {
            const response = await api.post('/daftarseminar', data, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            setMessage('Registration successful!');
            setTicketData(response.data.data); 
            localStorage.setItem('innoventure_seminar_ticket', JSON.stringify(response.data.data));
        } catch (error) {
            setMessage(error.response?.data?.message || 'Registration failed.');
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    if (ticketData) {
        return (
            <div className="max-w-md mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 text-center animate-fade-in-up">
                <div className="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">Ticket Secured!</h2>
                <p className="text-gray-500 mb-6">Screenshot this digital ticket or check your email for the backup.</p>
                
                <div className="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 mb-6 flex flex-col items-center">
                    <div className="bg-white p-4 rounded-xl shadow-sm mb-4">
                        <QRCodeSVG value={ticketData.kode_absen} size={200} level="H" />
                    </div>
                    
                    <div className="text-sm text-gray-500 uppercase font-semibold tracking-wider mb-1">Redeem Code</div>
                    <div className="text-3xl font-black tracking-widest text-indigo-600 dark:text-indigo-400 mb-4">{ticketData.kode_absen}</div>
                    
                    <div className="text-sm text-gray-500 uppercase font-semibold tracking-wider mb-1">Raffle Number</div>
                    <div className="text-lg font-bold text-gray-800 dark:text-gray-200">{ticketData.no_undian}</div>
                </div>

                <div className="p-4 bg-blue-50 text-blue-700 text-sm rounded-lg mb-6 border border-blue-100 text-left flex items-start">
                    <svg className="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>We have also sent this digital ticket to <strong>{formData.email}</strong>. Please check your inbox or spam folder.</span>
                </div>

                <div className="flex flex-col space-y-3 mt-6">
                    <button 
                        onClick={() => {
                            localStorage.removeItem('innoventure_seminar_ticket');
                            setTicketData(null);
                            setFormData({nama: '', instansi: '', email: '', no_hp: ''});
                            setMessage('');
                        }}
                        className="w-full py-3 px-4 rounded-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/30"
                    >
                        Daftar Peserta Lain
                    </button>
                    <Link to="/" className="w-full py-3 px-4 rounded-lg text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Kembali ke Halaman Utama
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <div className="max-w-3xl mx-auto">
            <div className="mb-10 text-center relative">
                <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[200px] h-[200px] bg-indigo-500/10 rounded-full blur-[60px] -z-10"></div>
                <h1 className="text-4xl font-black text-slate-900 mb-4 tracking-tight">Seminar Registration</h1>
                <p className="text-lg text-slate-500">Secure your digital ticket for the biggest National Tech Seminar.</p>
            </div>

            <div className="bg-white p-8 sm:p-10 rounded-3xl shadow-2xl shadow-indigo-100/50 border border-slate-100 relative overflow-hidden">
                <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-bl-full -z-0"></div>

                {message && (
                    <div className={`mb-8 p-4 rounded-xl text-sm font-medium flex items-center relative z-10 ${message.includes('success') ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100'}`}>
                        <svg className="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {message}
                    </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-8 relative z-10">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                            <input type="text" name="nama" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="John Doe" onChange={handleChange} />
                        </div>
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-2">Institution / University</label>
                            <input type="text" name="instansi" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="Universitas Indonesia" onChange={handleChange} />
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="john@example.com" onChange={handleChange} />
                        </div>
                        <div>
                            <label className="block text-sm font-bold text-slate-700 mb-2">WhatsApp Number</label>
                            <input type="text" name="no_hp" required className="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 transition-all outline-none" placeholder="081234567890" onChange={handleChange} />
                        </div>
                    </div>

                    <div className="bg-indigo-50/50 p-6 rounded-2xl border border-indigo-100/50">
                        <label className="block text-sm font-bold text-slate-800 mb-4 flex items-center">
                            <svg className="w-5 h-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Proof of Following @innoventure.ig
                        </label>
                        <input type="file" accept="image/*" className="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer file:transition-colors cursor-pointer" onChange={(e) => setBuktiFile(e.target.files[0])} />
                    </div>

                    <div className="flex items-center justify-between pt-6 border-t border-slate-100">
                        <Link to="/dashboard" className="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                            ← Back to Dashboard
                        </Link>
                        <button type="submit" disabled={isSubmitting} className="px-8 py-4 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-300 transform hover:-translate-y-1 shadow-xl shadow-indigo-200">
                            {isSubmitting ? 'Processing...' : 'Complete Registration'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

import { Link } from 'react-router-dom';
import { useState, useEffect, useRef } from 'react';
import bgImage from '../../assets/Backgroundwebsite.png';

// Komponen Animasi Scroll
function ScrollReveal({ children, className = '', delay = 0 }) {
    const [isVisible, setIsVisible] = useState(false);
    const domRef = useRef();

    useEffect(() => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => setIsVisible(true), delay);
                    if (domRef.current) observer.unobserve(domRef.current);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        const currentRef = domRef.current;
        if (currentRef) observer.observe(currentRef);
        return () => { if (currentRef) observer.unobserve(currentRef); };
    }, [delay]);

    return (
        <div
            ref={domRef}
            className={`transition-all duration-1000 ease-out transform ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-16'} ${className}`}
        >
            {children}
        </div>
    );
}

const timelineData = [
    { date: "Senin, 20 Juli 2026", title: "Pembukaan Pendaftaran", desc: "Registrasi resmi dibuka untuk seluruh cabang lomba (Web Dev, UI, dan ML)" },
    { date: "Sabtu, 8 Agustus 2026", title: "Penutupan Pendaftaran", desc: "Batas akhir pendaftaran dan pengumpulan administrasi peserta" },
    { date: "Senin, 10 Agustus 2026", title: "Opening Perlombaan", desc: "Pembukaan resmi acara sekaligus pembekalan regulasi dan teknis lomba" },
    { date: "Selasa, 11 Agustus - Senin, 18 Agustus 2026", title: "Sesi Lomba Web Dev, UI Design, ML", desc: "Waktu bagi peserta untuk merancang dan mengeksekusi karya, pada tanggal 13 dimulainya lomba ML, dan di tanggal 14 Final ML" },
    { date: "Senin, 19 Agustus 2026", title: "Pengumpulan Karya", desc: "Deadline final bagi peserta Web Dev dan UI untuk menyetor hasil karya." },
    { date: "Selasa, 19 Agustus - 22 Agustus 2026", title: "Penjurian Setiap Perlombaan", desc: "Waktu bagi peserta untuk merancang dan mengeksekusi karya" },
    { date: "Kamis, 22 Agustus 2026", title: "Pengumuman Top 3 dan juara ML", desc: "Perilisan daftar 3 besar finalis terbaik dari setiap cabang perlombaan" },
    { date: "Senin, 24 Agustus 2026", title: "Closing, Seminar, & Awarding", desc: "Hari puncak acara yang berisi seminar, penentuan juara utama, dan pembagian hadiah" },
];

export default function Landing() {
    const [medparts, setMedparts] = useState([]);
    const [sponsors, setSponsors] = useState([]);

    useEffect(() => {
        const fetchMedparts = async () => {
            try {
                const response = await fetch('/api/medpart');
                const data = await response.json();
                if (data.code === 200) {
                    setMedparts(data.payload || []);
                }
            } catch (error) {
                console.error("Failed to fetch medpart", error);
            }
        };
        const fetchSponsors = async () => {
            try {
                const response = await fetch('/api/sponsor');
                const data = await response.json();
                if (data.code === 200) {
                    setSponsors(data.payload || []);
                }
            } catch (error) {
                console.error("Failed to fetch sponsor", error);
            }
        };
        fetchMedparts();
        fetchSponsors();
    }, []);

    return (
        <div className="w-full">
            {/* Hero Section */}
            <section
                className="relative min-h-screen flex items-center justify-center overflow-hidden pt-20"
                style={{
                    backgroundImage: `url(${bgImage})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'no-repeat'
                }}
            >
                {/* Background Blobs */}
                <div className="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-brand-purple/20 rounded-full blur-[120px] -z-10 animate-pulse"></div>
                <div className="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-brand-pink/30 rounded-full blur-[100px] -z-10 animate-pulse" style={{ animationDelay: '1s' }}></div>

                <ScrollReveal className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                    <div className="animate-floating">
                        <div className="inline-block mb-6 px-3 py-2 md:px-4 md:py-2 rounded-full bg-brand-pink/10 border border-brand-pink/30 text-brand-purple font-semibold text-xs md:text-sm tracking-widest uppercase shadow-[0_0_15px_rgba(213,172,255,0.4)]">
                            Event IT se-Jawa Barat untuk SMA/SMK
                        </div>
                        <h2 className="text-xl sm:text-3xl md:text-5xl font-black text-brand-white tracking-widest mb-2 uppercase drop-shadow-lg">
                            Innovation Adventure
                        </h2>
                        <h1 className="text-4xl sm:text-7xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-pink tracking-tighter mb-2 leading-none drop-shadow-2xl">
                            INNOVENTURE
                        </h1>
                        <h2 className="text-2xl sm:text-4xl md:text-6xl font-black text-brand-pink tracking-widest mb-6 uppercase drop-shadow-lg">
                            CHAPTER II
                        </h2>
                        <p className="text-sm sm:text-xl md:text-2xl text-brand-white/90 max-w-3xl mx-auto px-4 mb-10 leading-relaxed font-medium drop-shadow-md">
                            "Code Your Passion, Create The Future, Be The Catalyst"
                        </p>
                        <div className="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <Link to="/seminar/register" className="px-10 py-4 rounded-full bg-brand-purple text-brand-white font-bold text-lg hover:bg-brand-pink hover:text-brand-black transition-all duration-300 shadow-xl shadow-brand-purple/20 transform hover:-translate-y-1 w-full sm:w-auto">
                                Daftar Seminar
                            </Link>
                            <a href="#about" className="px-10 py-4 rounded-full bg-transparent border-2 border-brand-purple text-brand-purple font-bold text-lg hover:bg-brand-purple hover:text-brand-white transition-all duration-300 w-full sm:w-auto">
                                Learn More
                            </a>
                        </div>
                    </div>
                </ScrollReveal>
            </section>

            {/* About Section */}
            <section id="about" className="py-24 bg-brand-black relative">
                <div className="absolute top-0 right-0 w-64 h-64 bg-brand-pink/10 rounded-bl-full -z-0"></div>
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                        <ScrollReveal>
                            <h2 className="text-4xl md:text-5xl font-black text-brand-white mb-6">What is Innoventure?</h2>
                            <div className="w-20 h-2 bg-brand-pink mb-8 rounded-full"></div>
                            <p className="text-gray-400 text-lg leading-relaxed mb-6">
                                Innoventure is an annual national technology event held by HIMF. It aims to bridge the gap between academic learning and industry demands by challenging students to solve real-world problems.
                            </p>
                            <p className="text-gray-400 text-lg leading-relaxed">
                                Whether you're a coder, a designer, or an esports enthusiast, Innoventure provides the perfect stage to showcase your talents and network with industry professionals.
                            </p>
                        </ScrollReveal>
                        <div className="grid grid-cols-2 gap-6">
                            <ScrollReveal delay={100} className="bg-gray-900 p-8 rounded-3xl border border-gray-800 text-center transform md:translate-y-8">
                                <div className="text-5xl font-black text-brand-pink mb-2">3+</div>
                                <div className="text-brand-white font-bold">Competitions</div>
                            </ScrollReveal>
                            <ScrollReveal delay={200} className="bg-gray-900 p-8 rounded-3xl border border-gray-800 text-center">
                                <div className="text-5xl font-black text-brand-purple mb-2">6Jt+</div>
                                <div className="text-brand-white font-bold">Prize Pool</div>
                            </ScrollReveal>
                            <ScrollReveal delay={300} className="bg-gray-900 p-8 rounded-3xl border border-gray-800 text-center transform md:translate-y-8">
                                <div className="text-5xl font-black text-brand-pink mb-2">200+</div>
                                <div className="text-brand-white font-bold">Participants</div>
                            </ScrollReveal>
                            <ScrollReveal delay={400} className="bg-gray-900 p-8 rounded-3xl border border-gray-800 text-center">
                                <div className="text-5xl font-black text-brand-purple mb-2">1</div>
                                <div className="text-brand-white font-bold">Grand Seminar</div>
                            </ScrollReveal>
                        </div>
                    </div>
                </div>
            </section>

            {/* Timeline Section */}
            <section id="timeline" className="py-24 bg-brand-black relative overflow-hidden border-t border-brand-purple/20">
                {/* Plus Pattern Background */}
                <div className="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] opacity-30 pointer-events-none"></div>
                <div className="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-brand-purple/10 rounded-full blur-[100px] -z-10 animate-pulse"></div>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

                    {/* Total Prizepool */}
                    <div className="text-center mb-16">
                        <ScrollReveal>
                            <h3 className="text-xl md:text-2xl font-black text-brand-white tracking-[0.2em] mb-4 uppercase">Total Prizepool</h3>
                            <h2 className="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-pink tracking-tighter mb-16 drop-shadow-[0_0_20px_rgba(213,172,255,0.3)]">
                                Rp. 6.000.000++
                            </h2>
                        </ScrollReveal>

                        <ScrollReveal delay={100}>
                            <h3 className="text-xl md:text-2xl font-black text-brand-white tracking-[0.2em] mb-4 uppercase">Open Registration</h3>
                            <h2 className="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-pink tracking-tighter mb-4 drop-shadow-[0_0_20px_rgba(213,172,255,0.3)]">
                                20 JULY - 9 AUGUST
                            </h2>
                        </ScrollReveal>
                    </div>

                    {/* Timeline Header */}
                    <ScrollReveal className="text-center mb-24 mt-20">
                        <h2 className="text-4xl md:text-5xl font-black text-brand-white tracking-[0.3em] uppercase drop-shadow-[0_0_15px_rgba(112,56,242,0.5)]">
                            TIMELINE
                        </h2>
                    </ScrollReveal>

                    {/* Horizontal Timeline (Desktop) */}
                    <div className="relative w-full max-w-7xl mx-auto hidden lg:block mb-10 overflow-x-auto pb-8 pt-8">
                        <div className="min-w-[1000px]">
                            {/* Horizontal Line */}
                            <div className="absolute top-[50%] left-0 w-full h-[2px] bg-brand-white -translate-y-1/2"></div>

                            <div className="flex justify-between relative w-full h-[320px]">
                                {timelineData.map((item, index) => (
                                    <div key={index} className="relative flex flex-col items-center flex-1">
                                        {/* Dot */}
                                        <div className="absolute top-[50%] left-1/2 w-6 h-6 bg-brand-purple rounded-full -translate-x-1/2 -translate-y-1/2 z-20 border-[4px] border-brand-black shadow-[0_0_10px_rgba(213,172,255,0.6)]"></div>

                                        {/* Connecting Line */}
                                        <div className={`absolute left-1/2 w-[2px] bg-brand-purple/50 -translate-x-1/2 z-10 ${index % 2 === 0 ? 'bottom-[50%] h-[40px]' : 'top-[50%] h-[40px]'}`}></div>

                                        {/* Content Box */}
                                        <div className={`w-full px-2 absolute ${index % 2 === 0 ? 'bottom-[calc(50%+40px)]' : 'top-[calc(50%+40px)]'} flex flex-col ${index % 2 === 0 ? 'justify-end' : 'justify-start'}`}>
                                            <h4 className="text-[11px] xl:text-[13px] font-bold text-brand-white mb-1 leading-tight">{item.date}</h4>
                                            <h3 className="text-xs xl:text-sm font-black text-brand-pink mb-1 leading-tight drop-shadow-md">{item.title}</h3>
                                            <p className="text-[10px] xl:text-[11px] text-gray-400 leading-snug">{item.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Vertical Timeline (Mobile & Tablet) */}
                    <div className="relative w-full mx-auto lg:hidden max-w-2xl px-4">
                        <div className="absolute top-0 left-8 w-[2px] h-full bg-brand-white"></div>
                        <div className="flex flex-col space-y-10 py-4">
                            {timelineData.map((item, index) => (
                                <div key={index} className="relative pl-14">
                                    <div className="absolute top-0 left-4 w-5 h-5 bg-brand-purple rounded-full -translate-x-1/2 z-10 border-4 border-brand-black shadow-[0_0_10px_rgba(213,172,255,0.6)]"></div>
                                    <h4 className="text-sm font-bold text-brand-white mb-1">{item.date}</h4>
                                    <h3 className="text-base font-black text-brand-pink mb-1">{item.title}</h3>
                                    <p className="text-xs text-gray-400 leading-relaxed">{item.desc}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            {/* Events Section */}
            <section id="events" className="py-24 bg-brand-white relative">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ScrollReveal className="text-center mb-16">
                        <h2 className="text-4xl md:text-5xl font-black text-brand-black mb-4">Featured Events</h2>
                        <p className="text-xl text-gray-500 max-w-2xl mx-auto">Discover the perfect arena to showcase your skills.</p>
                    </ScrollReveal>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        {/* Event Card 1 */}
                        <ScrollReveal delay={100} className="group bg-brand-white rounded-3xl p-8 shadow-xl shadow-brand-purple/5 border border-gray-100 hover:shadow-2xl hover:shadow-brand-purple/10 hover:-translate-y-2 transition-all duration-300">
                            <div className="w-16 h-16 bg-brand-purple/10 text-brand-purple rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold text-brand-black mb-3">Web Dev</h3>
                            <p className="text-gray-500 mb-6 line-clamp-3">Build innovative web applications that solve modern challenges. Compete for the ultimate prize.</p>
                            <Link to="/login" className="text-brand-purple font-bold hover:text-brand-pink inline-flex items-center">
                                Login <svg className="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                            </Link>
                        </ScrollReveal>

                        {/* Event Card 2 */}
                        <ScrollReveal delay={200} className="group bg-brand-white rounded-3xl p-8 shadow-xl shadow-brand-purple/5 border border-gray-100 hover:shadow-2xl hover:shadow-brand-purple/10 hover:-translate-y-2 transition-all duration-300">
                            <div className="w-16 h-16 bg-brand-pink/20 text-brand-purple rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold text-brand-black mb-3">UI/UX Design</h3>
                            <p className="text-gray-500 mb-6 line-clamp-3">Craft beautiful and intuitive user experiences. Show us your Figma wizardry and design thinking.</p>
                            <Link to="/login" className="text-brand-purple font-bold hover:text-brand-pink inline-flex items-center">
                                Login <svg className="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                            </Link>
                        </ScrollReveal>

                        {/* Event Card 3 */}
                        <ScrollReveal delay={300} className="group bg-brand-white rounded-3xl p-8 shadow-xl shadow-brand-purple/5 border border-gray-100 hover:shadow-2xl hover:shadow-brand-purple/10 hover:-translate-y-2 transition-all duration-300">
                            <div className="w-16 h-16 bg-gray-100 text-brand-black rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold text-brand-black mb-3">Mobile Legends</h3>
                            <p className="text-gray-500 mb-6 line-clamp-3">Gather your squad and fight your way to the top in the most intense esports tournament of the year.</p>
                            <Link to="/login" className="text-brand-purple font-bold hover:text-brand-pink inline-flex items-center">
                                Login <svg className="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                            </Link>
                        </ScrollReveal>

                        {/* Event Card 4 */}
                        <ScrollReveal delay={400} className="group bg-brand-black rounded-3xl p-8 shadow-xl shadow-brand-purple/20 border border-brand-purple/30 hover:-translate-y-2 transition-all duration-300">
                            <div className="w-16 h-16 bg-brand-purple/20 text-brand-purple rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-brand-purple/30">
                                <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                            </div>
                            <h3 className="text-2xl font-bold text-brand-white mb-3">Grand Seminar</h3>
                            <p className="text-gray-400 mb-6 line-clamp-3">Gain insights from top industry leaders. Get your digital ticket and join the tech conversation.</p>
                            <Link to="/seminar/register" className="text-brand-pink font-bold hover:text-brand-white inline-flex items-center">
                                Get Ticket <svg className="w-4 h-4 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clipRule="evenodd" /></svg>
                            </Link>
                        </ScrollReveal>
                    </div>
                </div>
            </section>

            {/* Sponsors Section */}
            {sponsors.length > 0 && (
                <section id="sponsors" className="py-12 bg-brand-black border-t border-brand-purple/20 relative overflow-hidden">
                    {/* Premium Glow Effect */}
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[250px] bg-brand-purple/10 rounded-full blur-[100px] pointer-events-none"></div>

                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                        <div className="text-center mb-10">
                            <div className="inline-block mb-3 px-5 py-1.5 rounded-full bg-brand-pink/10 border border-brand-pink/30 shadow-[0_0_10px_rgba(213,172,255,0.2)]">
                                <h3 className="text-xs md:text-sm font-bold text-brand-pink tracking-widest uppercase">Sponsored By</h3>
                            </div>
                            <h2 className="text-2xl md:text-3xl font-black text-brand-white tracking-tight">
                                Our Incredible Partners
                            </h2>
                        </div>
                        
                        <div className="flex flex-wrap justify-center items-center gap-6 md:gap-10">
                            {sponsors.map((partner) => (
                                <div key={partner.id} className="group relative flex items-center justify-center transition-all duration-300 hover:scale-105">
                                    {/* Hover Aura */}
                                    <div className="absolute inset-0 bg-brand-purple/30 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                                    
                                    <div className="relative bg-brand-white/5 border border-brand-white/10 backdrop-blur-sm rounded-2xl px-6 py-4 md:px-8 md:py-6 shadow-xl group-hover:border-brand-purple/50 group-hover:bg-brand-white/10 transition-all duration-300">
                                        <img 
                                            src={`/storage/${partner.logo}`} 
                                            alt={partner.nama} 
                                            className="h-14 md:h-20 max-w-[140px] md:max-w-[200px] object-contain drop-shadow-md"
                                            title={partner.nama}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Medpart Section */}
            {medparts.length > 0 && (
                <section id="partners" className="py-16 bg-brand-black border-t border-gray-900 relative">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-10">
                            <h3 className="text-xl md:text-2xl font-bold text-gray-500 tracking-widest uppercase">Supported By</h3>
                        </div>
                        <div className="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                            {medparts.map((partner) => (
                                <div key={partner.id} className="group relative flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 opacity-60 hover:opacity-100">
                                    <img 
                                        src={`/storage/${partner.logo}`} 
                                        alt={partner.nama} 
                                        className="h-16 md:h-24 object-contain"
                                        title={partner.nama}
                                    />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </div>
    );
}

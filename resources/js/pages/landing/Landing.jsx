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

export default function Landing() {
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
                        <div className="inline-block mb-6 px-4 py-2 rounded-full bg-brand-pink/10 border border-brand-pink/30 text-brand-purple font-semibold text-sm tracking-widest uppercase shadow-[0_0_15px_rgba(213,172,255,0.4)]">
                            Event IT se-Bandung untuk SMA/SMK & Mahasiswa
                        </div>
                        <h2 className="text-3xl md:text-5xl font-black text-brand-white tracking-widest mb-2 uppercase drop-shadow-lg">
                            Innovation Adventure
                        </h2>
                        <h1 className="text-7xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-purple to-brand-pink tracking-tighter mb-2 leading-none drop-shadow-2xl">
                            INNOVENTURE
                        </h1>
                        <h2 className="text-4xl md:text-6xl font-black text-brand-pink tracking-widest mb-6 uppercase drop-shadow-lg">
                            CHAPTER II
                        </h2>
                        <p className="text-xl md:text-2xl text-brand-white/90 max-w-3xl mx-auto mb-10 leading-relaxed font-medium drop-shadow-md">
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
        </div>
    );
}

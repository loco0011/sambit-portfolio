import { useEffect, useState } from 'react';
import { MotionConfig } from 'motion/react';
import { initSmoothScroll } from './lib/scroll';
import Loader, { shouldPlayIntro } from './components/Loader';
import Cursor from './components/Cursor';
import Nav from './components/Nav';
import CommandPalette from './components/CommandPalette';
import Hero from './components/Hero';
import Marquee from './components/Marquee';
import About from './components/About';
import Experience from './components/Experience';
import Work from './components/Work';
import Capabilities from './components/Capabilities';
import Principles from './components/Principles';
import Contact from './components/Contact';
import Footer from './components/Footer';

export default function App({ data }) {
    const [ready, setReady] = useState(false);
    const [intro] = useState(shouldPlayIntro);
    const [palette, setPalette] = useState(false);

    useEffect(() => initSmoothScroll(), []);

    useEffect(() => {
        const onKey = (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                setPalette((v) => !v);
            }
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, []);

    return (
        <MotionConfig reducedMotion="user">
            <Loader play={intro} name={data.profile.name} role={data.profile.role} onReveal={() => setReady(true)} />
            <Cursor />
            <div className="grain" aria-hidden />

            <Nav profile={data.profile} onPalette={() => setPalette(true)} />
            <CommandPalette open={palette} onClose={() => setPalette(false)} profile={data.profile} />

            <main>
                <Hero profile={{ ...data.profile, projectsTotal: data.projects_total }} ready={ready} handoff={intro} />
                <Marquee items={data.stack} />
                <About manifesto={data.manifesto} stats={data.stats} />
                <Experience items={data.experience} />
                <Work projects={data.projects} archive={data.archive ?? []} total={data.projects_total} />
                <Capabilities skills={data.skills} />
                <Principles principles={data.principles} education={data.education} />
                <Contact profile={data.profile} />
            </main>
            <Footer profile={data.profile} />
        </MotionConfig>
    );
}

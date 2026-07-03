@extends('layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #4c0f16;
        --bg-mid: #8f1521;
        --bg-bright: #ff5a66;
        --text: rgba(255, 245, 247, 0.88);
        --text-soft: rgba(255, 245, 247, 0.72);
        --line: rgba(255, 230, 233, 0.32);
        --glass: rgba(255, 255, 255, 0.08);
    }

    * { box-sizing: border-box; }

    html, body {
        margin: 0;
        min-height: 100%;
        font-family: "Segoe UI", Arial, sans-serif;
        background: #2b060c;
        color: var(--text);
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    .landing {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background:
            radial-gradient(circle at 72% 18%, rgba(255, 180, 188, 0.22), transparent 12%),
            radial-gradient(circle at 84% 66%, rgba(255, 120, 132, 0.16), transparent 16%),
            linear-gradient(115deg, #3d0a12 0%, #6d101a 28%, #a31724 62%, #ff5361 100%);
    }

    .landing::before,
    .landing::after {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .landing::before {
        background:
            linear-gradient(148deg, rgba(255, 255, 255, 0.11) 0 13%, transparent 13% 100%),
            linear-gradient(28deg, rgba(0, 0, 0, 0.16) 0 12%, transparent 12% 100%);
        opacity: 0.55;
        mix-blend-mode: screen;
    }

    .landing::after {
        background:
            radial-gradient(circle at 10% 18%, rgba(255, 255, 255, 0.06), transparent 22%),
            radial-gradient(circle at 78% 12%, rgba(255, 255, 255, 0.12), transparent 18%),
            radial-gradient(circle at 80% 78%, rgba(255, 255, 255, 0.1), transparent 20%);
        opacity: 0.7;
    }

    .poly {
        position: absolute;
        opacity: 0.82;
        filter: saturate(115%);
        transform-origin: center;
    }

    .p1 { inset: -6% auto auto -2%; width: 34%; height: 34%; background: linear-gradient(160deg, rgba(77, 4, 11, 0.95), rgba(160, 19, 35, 0.55)); clip-path: polygon(0 0, 100% 0, 0 100%); }
    .p2 { inset: 2% auto auto 18%; width: 29%; height: 27%; background: linear-gradient(155deg, rgba(184, 20, 41, 0.9), rgba(255, 81, 95, 0.24)); clip-path: polygon(0 12%, 100% 0, 74% 100%, 0 82%); }
    .p3 { inset: 0 auto auto 47%; width: 26%; height: 33%; background: linear-gradient(140deg, rgba(255, 99, 112, 0.88), rgba(144, 11, 22, 0.82)); clip-path: polygon(18% 0, 100% 18%, 68% 100%, 0 70%); }
    .p4 { inset: -2% 0 auto auto; width: 32%; height: 40%; background: linear-gradient(160deg, rgba(255, 151, 160, 0.5), rgba(228, 38, 54, 0.85)); clip-path: polygon(0 0, 100% 14%, 67% 100%, 0 62%); }
    .p5 { inset: 16% auto auto 0; width: 21%; height: 38%; background: linear-gradient(160deg, rgba(104, 7, 16, 0.88), rgba(234, 30, 49, 0.55)); clip-path: polygon(0 0, 82% 18%, 62% 100%, 0 70%); }
    .p6 { inset: 24% auto auto 39%; width: 18%; height: 26%; background: linear-gradient(140deg, rgba(255, 107, 124, 0.92), rgba(157, 15, 28, 0.88)); clip-path: polygon(18% 0, 100% 22%, 75% 100%, 0 82%); }
    .p7 { inset: 20% auto auto 67%; width: 22%; height: 34%; background: linear-gradient(145deg, rgba(255, 92, 106, 0.9), rgba(173, 18, 31, 0.85)); clip-path: polygon(0 18%, 100% 0, 82% 100%, 11% 86%); }
    .p8 { inset: 11% auto auto 79%; width: 23%; height: 38%; background: linear-gradient(160deg, rgba(255, 187, 194, 0.42), rgba(255, 67, 85, 0.9)); clip-path: polygon(0 0, 100% 10%, 69% 100%, 15% 86%); }
    .p9 { inset: 48% auto auto 9%; width: 20%; height: 30%; background: linear-gradient(150deg, rgba(163, 20, 34, 0.9), rgba(255, 88, 100, 0.35)); clip-path: polygon(0 0, 100% 16%, 70% 100%, 6% 80%); }
    .p10 { inset: 46% auto auto 31%; width: 18%; height: 30%; background: linear-gradient(150deg, rgba(246, 43, 64, 0.68), rgba(101, 4, 13, 0.85)); clip-path: polygon(16% 0, 100% 20%, 73% 100%, 0 74%); }
    .p11 { inset: 42% auto auto 51%; width: 22%; height: 34%; background: linear-gradient(145deg, rgba(255, 91, 103, 0.74), rgba(178, 20, 38, 0.9)); clip-path: polygon(0 14%, 88% 0, 100% 82%, 18% 100%); }
    .p12 { inset: 48% auto auto 72%; width: 28%; height: 42%; background: linear-gradient(160deg, rgba(255, 153, 160, 0.44), rgba(255, 71, 87, 0.92)); clip-path: polygon(0 0, 100% 10%, 78% 100%, 18% 84%); }
    .p13 { inset: 72% auto -10% 0; width: 29%; height: 38%; background: linear-gradient(150deg, rgba(111, 9, 18, 0.84), rgba(231, 42, 61, 0.6)); clip-path: polygon(0 0, 84% 14%, 57% 100%, 0 84%); }
    .p14 { inset: 66% auto -14% 24%; width: 30%; height: 40%; background: linear-gradient(150deg, rgba(204, 23, 43, 0.82), rgba(255, 82, 95, 0.54)); clip-path: polygon(0 10%, 100% 0, 78% 100%, 16% 84%); }
    .p15 { inset: 64% auto auto 52%; width: 21%; height: 28%; background: linear-gradient(150deg, rgba(255, 101, 115, 0.82), rgba(156, 17, 31, 0.82)); clip-path: polygon(10% 0, 100% 18%, 76% 100%, 0 80%); }
    .p16 { inset: 64% auto -18% 74%; width: 28%; height: 40%; background: linear-gradient(145deg, rgba(255, 162, 169, 0.4), rgba(255, 72, 88, 0.92)); clip-path: polygon(0 0, 100% 12%, 72% 100%, 10% 82%); }

    .wrap {
        position: relative;
        z-index: 2;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        padding: 28px clamp(20px, 4vw, 88px) 24px;
    }

    .nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 10px 0 16px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.15rem;
        letter-spacing: 0.02em;
        color: var(--text);
        text-transform: uppercase;
    }

    .brand-mark {
        width: 18px;
        height: 18px;
        position: relative;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.7);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.18);
    }

    .brand-mark::before {
        content: "";
        position: absolute;
        inset: -7px 2px auto 2px;
        height: 8px;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid rgba(255, 255, 255, 0.72);
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 28px;
        flex-wrap: wrap;
        justify-content: flex-end;
        font-size: 1rem;
        color: var(--text-soft);
    }

    .nav-links a {
        position: relative;
        padding-bottom: 4px;
        transition: color 0.2s ease;
    }

    .nav-links a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 1px;
        background: transparent;
        transition: background 0.2s ease;
    }

    .nav-links a:hover {
        color: white;
    }

    .nav-links a:hover::after {
        background: rgba(255, 255, 255, 0.6);
    }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 94px;
        padding: 9px 16px;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.08);
        color: white !important;
        backdrop-filter: blur(8px);
    }

    .pill::after {
        display: none;
    }

    .hero {
        flex: 1;
        display: flex;
        align-items: center;
        padding: clamp(36px, 7vw, 76px) 0;
    }

    .hero-copy {
        max-width: 920px;
        padding-top: 8vh;
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        font-size: 1rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.72);
    }

    .eyebrow::before {
        content: "";
        width: 54px;
        height: 1px;
        background: rgba(255, 255, 255, 0.35);
    }

    h1 {
        margin: 0;
        font-size: clamp(3.3rem, 7vw, 6.8rem);
        line-height: 0.94;
        font-weight: 800;
        letter-spacing: -0.04em;
        text-shadow: 0 10px 28px rgba(50, 0, 8, 0.2);
    }

    .subhead {
        margin: 24px 0 0;
        font-size: clamp(1.8rem, 3.5vw, 3.25rem);
        font-weight: 300;
        line-height: 1.08;
        color: rgba(255, 245, 247, 0.86);
    }

    .lead {
        max-width: 690px;
        margin: 28px 0 0;
        font-size: 1rem;
        line-height: 1.55;
        color: var(--text-soft);
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 28px;
        margin-top: 46px;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 165px;
        padding: 14px 22px;
        border-radius: 16px;
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 1.55rem;
        line-height: 1;
        text-align: center;
        backdrop-filter: blur(7px);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
        transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    }

    .button:hover {
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.16);
        border-color: rgba(255, 255, 255, 0.46);
    }

    .tagline {
        margin-top: auto;
        padding: 6px 0 2px;
        color: rgba(255, 255, 255, 0.35);
        font-size: 1rem;
    }

    @media (max-width: 900px) {
        .nav {
            align-items: flex-start;
            flex-direction: column;
        }

        .nav-links {
            gap: 16px;
        }

        .hero-copy {
            padding-top: 3vh;
        }

        .actions {
            gap: 14px;
        }

        .button {
            min-width: 140px;
            font-size: 1.2rem;
        }
    }

    @media (max-width: 640px) {
        .wrap {
            padding-inline: 16px;
        }

        h1 {
            font-size: clamp(2.8rem, 15vw, 4.2rem);
        }

        .subhead {
            font-size: clamp(1.5rem, 7vw, 2.4rem);
        }
    }
</style>

<main class="landing">
    <span class="poly p1"></span>
    <span class="poly p2"></span>
    <span class="poly p3"></span>
    <span class="poly p4"></span>
    <span class="poly p5"></span>
    <span class="poly p6"></span>
    <span class="poly p7"></span>
    <span class="poly p8"></span>
    <span class="poly p9"></span>
    <span class="poly p10"></span>
    <span class="poly p11"></span>
    <span class="poly p12"></span>
    <span class="poly p13"></span>
    <span class="poly p14"></span>
    <span class="poly p15"></span>
    <span class="poly p16"></span>

    <div class="wrap">
        <header class="nav">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark" aria-hidden="true"></span>
                <span>Garment Company</span>
            </a>

            <nav class="nav-links" aria-label="Primary">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}">Profile</a>
                <a href="{{ route('home') }}">About Us</a>
                <a href="{{ route('home') }}">Contacts</a>
                <a class="pill" href="{{ route('login') }}">Login</a>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-copy">
                <div class="eyebrow">Garment Management</div>
                <h1>Well Come </h1>
                <p class="lead">
                    Manage garments, stock, and sales with a clean storefront and an admin workflow built for a
                    modern clothing business.
                </p>

                <div class="actions">
                    <a class="button" href="{{ route('dashboard') }}">Login</a>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

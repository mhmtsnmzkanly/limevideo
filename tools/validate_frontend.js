const fs = require('fs');
const vm = require('vm');

const html = fs.readFileSync('index.html', 'utf8');
const js = fs.readFileSync('index.js', 'utf8');

function extractTemplates(source) {
    const templates = {};
    const pattern = /<template\s+id="([^"]+)"[^>]*>([\s\S]*?)<\/template>/g;
    for (const match of source.matchAll(pattern)) {
        templates[match[1]] = match[2];
    }
    return templates;
}

function extractDataActions(source) {
    return [...new Set([...source.matchAll(/data-action="([^"]+)"/g)].map(match => match[1]))].sort();
}

function createSandbox(templates) {
    return {
        console,
        setTimeout,
        clearTimeout,
        setInterval,
        clearInterval,
        history: { pushState() {} },
        sessionStorage: { getItem() { return ''; }, setItem() {} },
        navigator: { clipboard: { writeText() {} } },
        location: { pathname: '/gallery', hash: '', origin: 'https://example.test' },
        window: {
            addEventListener() {},
            location: { pathname: '/gallery', hash: '', origin: 'https://example.test' },
            crypto: { randomUUID() { return 'sess_test'; } },
            innerWidth: 1280,
            innerHeight: 720
        },
        document: {
            getElementById(id) {
                if (templates[id]) {
                    return {
                        innerHTML: templates[id],
                        dataset: {},
                        classList: { add() {}, remove() {}, contains() { return false; } }
                    };
                }
                return {
                    innerHTML: '',
                    dataset: {},
                    classList: { add() {}, remove() {}, contains() { return false; }, toggle() {} },
                    style: {},
                    value: '',
                    textContent: '',
                    disabled: false,
                    querySelector() { return null; }
                };
            },
            addEventListener() {},
            documentElement: { scrollHeight: 1000 }
        }
    };
}

function sampleContext() {
    return {
        page: 'gallery',
        unreadCount: 0,
        notificationDotHtml: '',
        chatUnreadCount: 0,
        chatBadgeLabel: 0,
        chatBadgeHidden: 'hidden',
        me: { id: 'u_demo1', username: 'demo', display_name: 'Demo' },
        n: { url: '/gallery', type: 'X', title: '<T>', body: '<B>', created_at: 'now', is_read: 0 },
        message: { user_id: 'u_demo1', display_name: '<Demo>', username: 'demo', body: '<Hi>', created_at: 'now' },
        isOwn: true,
        c: { id: 'c1', username: 'demo', display_name: 'Demo', body: '<Body>', created_at: 'now', votes_sum: 0, user_vote: '', video_title: 'Video' },
        v: { id: 'v1', user_id: 'u_demo1', title: '<Video>', username: 'demo', display_name: 'Demo', duration: 1, views: 0, comments_count: 0, followers_count: 0, is_following: 0, is_saved: 0, votes_sum: 0, created_at: 'now', description: '<Desc>', disable_comments: 0, thumbnail_url: '' },
        u: { id: 'u_demo1', username: 'demo', display_name: '<Demo>', bio: '<Bio>', avatar_url: '', cover_url: '', stats: { videos: 0, views: 0, followers: 0, following: 0, saved: 0 }, is_following: 0 },
        ad: { label: 'Ad', title: '<Ad>', body: '<Body>' },
        title: '<T>',
        resultLabel: 'R',
        categoryPills: '',
        cards: '',
        labelHtml: '<b>All</b>',
        message: '<Empty>',
        items: '',
        label: 'L',
        field: 'autoplay',
        description: 'D',
        value: true,
        currentVideo: { id: 'v1', username: 'demo' },
        currentIndex: 0,
        total: 1,
        video: { id: 'v1', title: 'Video', duration: 1, views: 0 },
        activeOverlayHtml: '',
        isReply: false,
        replyLineHtml: '',
        replyButtonHtml: '',
        replyBoxHtml: '',
        repliesHtml: '',
        activeTab: '#videos',
        formHtml: '',
        searchPanel: '',
        contentHtml: '',
        feedHtml: '',
        leaderboardHtml: '',
        tagsHtml: '',
        tag: { name: 'Design', slug: 'design' },
        rowsHtml: '',
        playlistHtml: '',
        suggestionsHtml: '',
        sidebarAd: {},
        vi: { id: 'v2', title: 'V2', username: 'demo', views: 0 },
        coverInlineStyle: '',
        actionsHtml: '',
        viewToggleHtml: '',
        tabHtml: '',
        avatarHtml: '',
        thumbnailHtml: '',
        posterUrl: '',
        displayName: '<Demo>',
        url: 'https://example.test/x.png',
        className: 'video-thumb-img',
        commentFormHtml: ''
    };
}

const templates = extractTemplates(html);
const sandbox = createSandbox(templates);
vm.createContext(sandbox);
vm.runInContext(`${js}\nglobalThis.__app = app;`, sandbox);

const app = sandbox.__app;
const actionNames = extractDataActions(html);
const handlerGroups = [app.actions, app.changeActions, app.inputActions, app.keyActions, app.submitActions];
const missingActions = actionNames.filter(action => !handlerGroups.some(group => group && typeof group[action] === 'function'));

let failedTemplates = 0;
for (const id of Object.keys(templates)) {
    try {
        app.renderTemplate(id.replace(/^tpl-/, ''), sampleContext());
    } catch (error) {
        failedTemplates += 1;
        console.error(`Template failed: ${id}`, error);
    }
}

if (missingActions.length) {
    console.error(`Missing data-action handlers: ${missingActions.join(', ')}`);
}

if (missingActions.length || failedTemplates) {
    process.exit(1);
}

console.log(`Frontend validation passed: ${actionNames.length} actions, ${Object.keys(templates).length} templates.`);

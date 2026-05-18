const fs = require('fs');
const vm = require('vm');

const html = fs.readFileSync('../ui/index.html', 'utf8');
const js = fs.readFileSync('../ui/index.js', 'utf8');

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

function extractTemplateExpressions(source) {
    return [...source.matchAll(/\$\{([^}]+)\}/g)].map(match => match[1].trim());
}

function extractRawTemplateFragments(source) {
    const match = source.match(/rawTemplateFragments:\s*new Set\(\[([\s\S]*?)\]\)/);
    if (!match) return [];
    return [...match[1].matchAll(/"([^"]+)"/g)].map(item => item[1]);
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
        categoryPillsHtml: '',
        skeletonCardsHtml: '',
        categoryLabelHtml: '<b>All</b>',
        message: '<Empty>',
        itemsHtml: '',
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
        authFormHtml: '',
        searchPanelHtml: '',
        galleryContentHtml: '',
        galleryFeedHtml: '',
        leaderboardHtml: '',
        tagsHtml: '',
        tag: { name: 'Design', slug: 'design' },
        settingsRowsHtml: '',
        statsHtml: '',
        trendingHtml: '',
        listsHtml: '',
        adServicesHtml: '',
        playlistHtml: '',
        suggestionsHtml: '',
        sidebarAd: {},
        vi: { id: 'v2', title: 'V2', username: 'demo', views: 0 },
        coverInlineStyle: '',
        actionsHtml: '',
        viewToggleHtml: '',
        profileTabHtml: '',
        avatarHtml: '',
        thumbnailHtml: '',
        posterUrl: '',
        displayName: '<Demo>',
        url: 'https://example.test/x.png',
        sourceUrl: 'https://example.test/video.mp4',
        className: 'video-thumb-img',
        commentFormHtml: '',
        playerHtml: '',
        stat: { icon: 'fa-solid fa-play', label: 'Videos', value: '12' },
        list: { name: 'Watch Later', item_count: 2, updated_at: 'now' },
        service: { display_name: 'Internal Ads', service: 'internal', status: 'Enabled' }
    };
}

const templates = extractTemplates(html);
const broadRawFragments = new Set([
    'cards',
    'categoryPills',
    'contentHtml',
    'feedHtml',
    'formHtml',
    'items',
    'labelHtml',
    'rowsHtml',
    'searchPanel',
    'tabHtml'
]);
const rawFragments = extractRawTemplateFragments(js);
const unsafeRawFragments = rawFragments.filter(name => broadRawFragments.has(name));
const templateExpressions = extractTemplateExpressions(html);
const unapprovedHtmlFragments = templateExpressions.filter(expression => {
    if (!/Html$/.test(expression)) return false;
    return !rawFragments.includes(expression);
});
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

const xssPayload = '<img src=x onerror=alert(1)>';
const renderedXssChecks = [
    app.renderTemplate('partial-video-card', {
        v: { id: 'v_xss', title: xssPayload, username: xssPayload, duration: 1, views: 0 },
        thumbnailHtml: ''
    }),
    app.renderTemplate('partial-comment', {
        c: { id: 'c_xss', username: xssPayload, body: xssPayload, created_at: 'now', votes_sum: 0, user_vote: '' },
        isReply: false,
        replyLineHtml: '',
        replyButtonHtml: '',
        replyBoxHtml: '',
        repliesHtml: ''
    }),
    app.renderTemplate('partial-profile-about', {
        u: { bio: xssPayload }
    })
];
const unsafeRenderedPayload = renderedXssChecks.find(output =>
    output.includes('<img src=x')
);

if (missingActions.length) {
    console.error(`Missing data-action handlers: ${missingActions.join(', ')}`);
}

if (unsafeRawFragments.length) {
    console.error(`Unsafe broad raw template fragments: ${unsafeRawFragments.join(', ')}`);
}

if (unapprovedHtmlFragments.length) {
    console.error(`Raw HTML template expressions missing whitelist entry: ${unapprovedHtmlFragments.join(', ')}`);
}

if (unsafeRenderedPayload) {
    console.error('Template escaping smoke check failed: raw XSS payload was rendered.');
}

if (missingActions.length || failedTemplates || unsafeRawFragments.length || unapprovedHtmlFragments.length || unsafeRenderedPayload) {
    process.exit(1);
}

console.log(`Frontend validation passed: ${actionNames.length} actions, ${Object.keys(templates).length} templates.`);

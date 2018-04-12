<?php

namespace Potherca\PlantUml\DiagramGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$content = '';

$fileContents = file_get_contents(dirname(__DIR__).'/build/diagrams.txt');

$fileContents = html_entity_decode($fileContents);

preg_match_all('#./(?P<FILE>[^:]+):(?<DIAGRAM>@startuml.*?@enduml)#ms', $fileContents, $matches, PREG_SET_ORDER);

$diagrams = [];

$replace = [
    '!include ArrayList.iuml' => "class ArrayList\n!ifdef SHOW_METHODS\nclass ArrayList {\n  int size()\n  void clear()\n}\n!endif\n",
    '!include List.iuml' => "interface List\nList : int size()\nList : void clear()\n",
    '<img sourceforge.jpg>' => '<img:https://upload.wikimedia.org/wikipedia/commons/c/c8/SourceForge_logo_2011.png>',
    '<img:sourceforge.jpg>' => '<img:https://upload.wikimedia.org/wikipedia/commons/c/c8/SourceForge_logo_2011.png>',
];

$fileNameMap = [
    'archimate-diagram-001' => 'archimate-diagram-a14hbkae',
    'archimate-diagram-002' => 'archimate-diagram-4ngcmnbi',
    'archimate-diagram-003' => 'archimate-diagram-psj3fkvt',
    'archimate-diagram' => 'archimate-diagram-yhclsvoa',
    'ascii-math-001' => 'ascii-math-9p2hhe1i',
    'ascii-math' => 'ascii-math-lmwkmhft',
    'commons-001' => 'commons-6kmvhslk',
    'commons-002' => 'commons-vqou7kzw',
    'commons-003' => 'commons-eac0jsat',
    'commons-004' => 'commons-0qz1tmrw',
    'commons-005' => 'commons-3ffcjxfo',
    'commons' => 'commons-s6mrzzlf',
    'creole-001' => 'creole-apw7vduj',
    'creole-002' => 'creole-lrvdiwdi',
    'creole-003' => 'creole-ru1rgmtj',
    'creole-004' => 'creole-kqkopfyv',
    'creole-005' => 'creole-lt9teztk',
    'creole-006' => 'creole-onskwhs4',
    'creole-007' => 'creole-xclygy4s',
    'creole-008' => 'creole-jxtpwbpp',
    'creole-009' => 'creole-azoyzmwh',
    'creole-010' => 'creole-vlizmsb0',
    'creole-011' => 'creole-pudpdca8',
    'creole' => 'creole-r5x2wfn2',
    'handwritten-001' => 'handwritten-u6y27bly',
    'handwritten-002' => 'handwritten-lsvpjidg',
    'handwritten' => 'handwritten-twb4kzwd',
    'link-001' => 'link-ckjdma8d',
    'link-002' => 'link-uexstc56',
    'link-003' => 'link-itwlljmr',
    'link' => 'link-uv2kmuak',
    'preprocessing-001' => '',
    'preprocessing-002' => '',
    'preprocessing-003' => '',
    'preprocessing-004' => '',
    'preprocessing-005' => 'preprocessing-s0keyesl',
    'preprocessing-006' => 'preprocessing-zjmhqqmo',
    'preprocessing-007' => 'preprocessing-mhy3emiy',
    'preprocessing-008' => 'preprocessing-dluvs3qb',
    'preprocessing-009' => 'preprocessing-0v5ybgmj',
    'preprocessing-010' => 'preprocessing-1n1jqrsg',
    'preprocessing-011' => 'preprocessing-sxcpk1fu',
    'preprocessing-012' => 'preprocessing-tafiskgo',
    'preprocessing-013' => 'preprocessing-arl93ujd',
    'preprocessing-014' => 'preprocessing-f26n7lqa',
    'preprocessing-015' => 'preprocessing-mu6hkc62',
    'preprocessing-016' => 'preprocessing-rih3xaou',
    'preprocessing-017' => 'preprocessing-ps0syt4o',
    'preprocessing' => 'preprocessing-ps0syt4o',
    'sequence-diagram-001' => 'sequence-diagram-b7iuagro',
    'sequence-diagram-002' => 'sequence-diagram-03kvxmgq',
    'sequence-diagram-003' => 'sequence-diagram-ekugtarg',
    'sequence-diagram-004' => 'sequence-diagram-5kyi4nr9',
    'sequence-diagram-005' => 'sequence-diagram-wmea7pjc',
    'sequence-diagram-006' => 'sequence-diagram-fsbdbgzm',
    'sequence-diagram-007' => 'sequence-diagram-etrvgzp5',
    'sequence-diagram-008' => 'sequence-diagram-jwyfejkz',
    'sequence-diagram-009' => 'sequence-diagram-cthbw9yh',
    'sequence-diagram-010' => 'sequence-diagram-tgexgujs',
    'sequence-diagram-011' => 'sequence-diagram-5oyjczlt',
    'sequence-diagram-012' => 'sequence-diagram-nc6un0w1',
    'sequence-diagram-013' => 'sequence-diagram-hchldqnm',
    'sequence-diagram-014' => 'sequence-diagram-zmmoe7rz',
    'sequence-diagram-015' => 'sequence-diagram-lhe7qdrx',
    'sequence-diagram-016' => 'sequence-diagram-psd2c8do',
    'sequence-diagram-017' => 'sequence-diagram-hqx7beev',
    'sequence-diagram-018' => 'sequence-diagram-gafljnf9',
    'sequence-diagram-019' => 'sequence-diagram-zanrggjr',
    'sequence-diagram-020' => 'sequence-diagram-tpweflpm',
    'sequence-diagram-021' => 'sequence-diagram-on4wtob2',
    'sequence-diagram-022' => 'sequence-diagram-4iqbggrn',
    'sequence-diagram-023' => 'sequence-diagram-ejg1znha',
    'sequence-diagram-024' => 'sequence-diagram-pmvrc5bz',
    'sequence-diagram-025' => 'sequence-diagram-8ff6ywfh',
    'sequence-diagram-026' => 'sequence-diagram-3tqw3dbn',
    'sequence-diagram-027' => 'sequence-diagram-wzp5c3zj',
    'sequence-diagram-028' => 'sequence-diagram-mqs2wnwy',
    'sequence-diagram-029' => 'sequence-diagram-sbhpenum',
    'sequence-diagram-030' => 'sequence-diagram-do1e2yo2',
    'sequence-diagram-031' => 'sequence-diagram-r1fpfaia',
    'sequence-diagram-032' => 'sequence-diagram-vztj3zmp',
    'sequence-diagram-033' => 'sequence-diagram-g9grfpgj',
    'sequence-diagram-034' => 'sequence-diagram-jlwosp36',
    'sequence-diagram-035' => 'sequence-diagram-k0tzkzby',
    'sequence-diagram-036' => 'sequence-diagram-lglfuodc',
    'sequence-diagram-037' => 'sequence-diagram-boupavwd',
    'sequence-diagram' => 'sequence-diagram-cyl73aug',
    'sprite-001' => 'sprite-xzlhsxf8',
    'sprite-002' => 'sprite-7k0uol9q',
    'sprite-003' => 'sprite-grxlj1so',
    'sprite' => 'sprite-s5wuxbkt',
    'timing-diagram-001' => 'timing-diagram-od4c43qp',
    'timing-diagram-002' => 'timing-diagram-x2qmsfmb',
    'timing-diagram-003' => 'timing-diagram-dzbputel',
    'timing-diagram-004' => 'timing-diagram-vdtmlixd',
    'timing-diagram-005' => 'timing-diagram-opvma4mx',
    'timing-diagram-006' => 'timing-diagram-e5ioqlhq',
    'timing-diagram-007' => 'timing-diagram-ui2tf0me',
    'timing-diagram' => 'timing-diagram-evx5w9k7',
    'unicode-001' => 'unicode-bxk3oaa6',
    'unicode-002' => 'unicode-imurfx8n',
    'unicode-003' => 'unicode-yhgf9azr',
    'unicode' => 'unicode-pibmqzjf',
];

$fileNameCounter = [];

$ignoreList = [
    'api',
    'archimate',
    'dedication',
    'code-groovy',
    'color',
    'donors',
    'doxygen',
    'faq',
    'faq-install',
    'font',
    'graphviz-dot',
    'javadoc',
    'je-suis-charlie',
    'latex',
    'oregon-trail',
    'pmwiki',
    'plantuml',
    'statistics-report',
    'skinparam',
    'smetana',
    'smetana02',
    'sources',
    'starting',
    'steve',
    'sudoku',
    'svg',
    'teoz',
    'tinymce',
    'versioning-scheme',
    'vizjs',
    'xearth',

    // @TODO: The files below have special syntax that still needs work
    'ditaa',    // "@startditaa"
    'dot',      // "@startdot"
    'gantt',    // "@startgantt"
    'jcckit',   // "@startjcckit"
    'salt',     // "@startsalt"
];

array_walk($matches, function ($match) use (&$diagrams, &$fileNameCounter, $ignoreList, $replace) {

    $fileName = basename($match['FILE'], '.html');

    if (in_array($fileName, $ignoreList) === false) {

        $key = $fileName;

        if (array_key_exists($fileName, $fileNameCounter) === false) {
            $fileNameCounter[$fileName] = '000';
        } else {
            $key .= '-'.$fileNameCounter[$fileName];
        }

        $fileNameCounter[$fileName] = sprintf('%03d', $fileNameCounter[$fileName]+1);

        $diagram = $match['DIAGRAM'];

        $diagram = str_replace(array_keys($replace), array_values($replace), $diagram);

        $diagrams[$key] = $diagram;
    }
});

array_walk($fileNameMap, function ($to, $from) use (&$diagrams) {
    $diagrams[$to] = $diagrams[$from];
    unset($diagrams[$from]);
});

array_walk($diagrams, function ($diagram, $key) use (&$diagrams) {
    if (strpos($diagram, 'listopeniconic') !== false || $key === '') {
        unset($diagrams[$key]);
    }
});

ksort($diagrams);

if (isset($argv)) {
    is_dir(dirname(__DIR__).'/build/diagrams') || mkdir(dirname(__DIR__).'/build/diagrams');
    array_walk($diagrams, function ($diagram, $key) {
        file_put_contents(dirname(__DIR__).'/build/diagrams/'.$key.'.puml', $diagram);
    });
} else {
    $images = array_map(function ($diagram) {
        $encode = \Jawira\PlantUml\encodep($diagram);

        return vsprintf('http://www.plantuml.com/plantuml/png/%s"', [$encode]);
    }, $diagrams);

    array_walk($images, function ($imageSource, $imageName) use (&$content, $diagrams) {
        $content .= vsprintf(<<<'HTML'
    <li>
        <span>%s.png</span>
            <pre>%s</pre>
        <img src="%s" title="%2$s" />
    </li>
HTML
            , [$imageName, htmlentities($diagrams[$imageName]), $imageSource]
        );
    });

    echo vsprintf(<<<'HTML'
    <!doctype html>
    <html>
    <link rel="stylesheet" href="style.css" />
    <ul>
        %s
    </ul>
    </html>
HTML
        ,[$content]
    );
}

/*EOF*/

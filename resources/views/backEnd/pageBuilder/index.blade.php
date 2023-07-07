<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/css/grapes.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/fontawesome.min.css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <style>
        #gjs {
            border: 3px solid #444;
        }

        .gjs-cv-canvas {
            top: 0;
            width: 100%;
            height: 100%;
        }

        .gjs-block {
            width: auto;
            height: auto;
            min-height: auto;
        }
    </style>
</head>
<body>
    <div id="gjs">
        
    </div>
    <div id="blocks"></div>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.12.17/grapes.min.js"></script>
      <script>
          const editor = grapesjs.init({
              // Indicate where to init the editor. You can also pass an HTMLElement
              container: '#gjs',
              
              blockManager: {
                appendTo: '#blocks',
                blocks: [
                {
                    id: 'section', // id is mandatory
                    label: '<b>Section</b>', // You can use HTML/SVG inside labels
                    attributes: { class:'gjs-block-section' },
                    content: `<section>
                    <h1>This is a simple title</h1>
                    <div>This is just a Lorem text: Lorem ipsum dolor sit amet</div>
                    </section>`,
                }, {
                    id: 'text',
                    label: 'Text',
                    content: '<div data-gjs-type="text">Insert your text here</div>',
                },
                {
                    id: 'navbar',
                    label: 'NavBar',
                    content: '<div data-gjs-type="navbar">test</div>',
                },
                 {
                    id: 'image',
                    label: 'Image',
                    media: `<svg style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z" />
                    </svg>`,
                    // Use `image` component
                    content: { type: 'image' },
                    // The component `image` is activatable (shows the Asset Manager).
                    // We want to activate it once dropped in the canvas.
                    activate: true,
                    // select: true, // Default with `activate: true`
                }
                ]
            },
          });

          var blockManager = editor.BlockManager;
            blockManager.add('bs-container', {
                label: 'Container',
                attributes: {class: 'fa fa-window-maximize'},
                content: {
                    components: "<div class='pbcontainer' data-gjs-draggable='true' data-gjs-editable='true' data-gjs-removable='true' data-gjs-propagate='" + ["removable", "editable", "draggable"] + "'>test</div>"
                },
                category: 'Basic'
            });
      </script>
</body>
</html>

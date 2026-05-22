import{j as s}from"./app-B65D-WcN.js";const r=()=>s.jsxs("svg",{id:"icon-check-mark",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 150 150",style:{maxWidth:"150px",display:"block"},children:[s.jsx("style",{children:`
        .stroke {
          fill: none;
          stroke-width: 10;
          stroke-linejoin: round;
        }

        #circle {
          stroke-dasharray: 345;
          stroke-dashoffset: 346;
          transform-origin: center;
          transform: rotate(0deg);
          animation: circle-dash 0.7s 1 cubic-bezier(0.42, 0, 1, 1) forwards;
        }

        @keyframes circle-dash {
          to {
            stroke-dashoffset: 0;
            transform: rotate(360deg);
          }
        }

        #check {
          stroke-linecap: square;
          stroke-dasharray: 122;
          stroke-dashoffset: -122;
          animation: check-dash 0.3s 1 cubic-bezier(0.19, 1, 0.22, 1) 0.65s forwards;
        }

        @keyframes check-dash {
          to {
            stroke-dashoffset: 0;
          }
        }
      `}),s.jsx("path",{id:"circle",className:"stroke stroke-eng-primary",d:"M141,69v6c0,37.5-31.3,67.7-69.2,65.9c-33.8-1.6-61.2-29-62.8-62.8C7.3,40.2,37.6,9,75,9c9.2,0,18.4,2,26.8,5.7"}),s.jsx("path",{id:"check",className:"stroke stroke-eng-secondary",d:"M139.9,22.2l-66,66.1L54.1,68.5"})]});export{r as A};

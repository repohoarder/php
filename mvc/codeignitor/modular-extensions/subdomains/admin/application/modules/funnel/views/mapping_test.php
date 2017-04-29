                
                <br><br><br>
                
                <div class="node" id="block1" style="position: absolute;">Block1</div>
                
                <br><br><br>
                
                <div class="node" id="block2" style="position: absolute;">Block2</div>
                       
                       
                <script type="text/javascript">
               
                var targetOption = {anchor:"TopCenter",
                                                    maxConnections:-1,
                                                    isSource:false,
                                                    isTarget:true,
                                                    endpoint:["Dot", {radius:5}],
                                                    paintStyle:{fillStyle:"#66FF00"},
                                                        setDragAllowedWhenFull:true}
                                                       
                var sourceOption = {anchor:"BottomCenter",
                                                        maxConnections:-1,
                                                    isSource:true,
                                                    isTarget:false,
                                                    endpoint:["Dot", {radius:5}],
                                                    paintStyle:{fillStyle:"#FFEF00"},
                                                        setDragAllowedWhenFull:true}
               
                jsPlumb.bind("ready", function() {
                       
                        jsPlumb.addEndpoint('block1', targetOption);
                        jsPlumb.addEndpoint('block1', sourceOption);
                       
                        jsPlumb.addEndpoint('block2', targetOption);
                        jsPlumb.addEndpoint('block2', sourceOption);
                       
                        jsPlumb.draggable('block1');
                        jsPlumb.draggable('block2');
                });
               
                </script>

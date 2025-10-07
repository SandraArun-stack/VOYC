
    <div class="tshirt-container">
        <h2>T-Shirt Designer</h2>

        <div id="controls">
            <label>Dress Color: <input type="color" id="colorPicker" value="#9e9e9e"></label>
            <button id="addText">Add Text</button>
            <label>Text Color: <input type="color" id="textColor" value="#000000"></label>
            <br><br>

            <label>Font:
                <select id="fontFamily">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Comic Sans MS">Comic Sans</option>
                </select>
            </label>

            <label><input type="checkbox" id="boldToggle"> Bold</label>
            <label><input type="checkbox" id="italicToggle"> Italic</label>

            <label>Size: <input type="range" id="fontSize" min="10" max="80" value="20"></label>
            <br><br>

            <input type="file" id="uploadImage" accept="image/*">
            <button id="cropBtn" disabled title="(Not implemented in this snippet)">Crop Image</button>
            <button id="resetOverlayBtn">Reset Dress Position</button>
            <label class="small"><input type="checkbox" id="lockOverlay"> Lock Dress</label>
            <br><br>

            <button id="deleteBtn">Delete Selected</button>
            <button id="saveBtn">Save Design</button>
        </div>

        <div class="thumbs">
            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/06_1.png" data-src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/06_1.png">
            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/09_1.png" data-src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/09_1.png">
            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/08.png" data-src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/08.png">
            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/07.png" data-src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/07.png">
            <img src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/05.png" data-src="<?= base_url() . ASSET_PATH; ?>assets/img/shirtimages/05.png">
        </div>

        <div id="designer-container">
            <canvas id="tshirtCanvas" width="400" height="500"></canvas>
        </div>
    </div>
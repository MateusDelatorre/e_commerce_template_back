package com.example.demo.data.schema;

import com.example.demo.domain.entities.product.Image;
import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.sql.Blob;

@Entity
@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
@Table(name = "image")
public class ImageSchema {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String fileName;
    private String fileType;
    private String downloadURl;
    @Lob
    private Blob image;
    @ManyToOne
    @JoinColumn(name = "product_id")
    private ProductSchema product;

    public ImageSchema(Image image){
        this.id = image.getId();
        this.fileName = image.getFileName();
        this.fileType = image.getFileType();
        this.downloadURl = image.getDownloadURl();
        this.image = image.getImage();
        this.product = new ProductSchema(image.getProduct());
    }

    public Image toImage(){
        return new Image(
                this.id,
                this.fileName,
                this.fileType,
                this.downloadURl,
                this.image,
                this.product.toProduct());
    }
}

package com.example.demo.domain.entities.product;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.sql.Blob;

@Data
@AllArgsConstructor
@NoArgsConstructor
public class Image {
    private Long id;
    private String fileName;
    private String fileType;
    private String downloadURl;
    private Blob image;
    private Product product;
}
